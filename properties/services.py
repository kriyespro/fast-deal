from django.db.models import Q, F
from .models import Property, PropertyStatus, Inquiry, SavedProperty, Project, ProjectStatus


def get_active_properties(filters=None):
    qs = Property.objects.filter(status=PropertyStatus.ACTIVE).select_related('city', 'locality', 'broker').prefetch_related('images')
    if not filters:
        return qs

    city = filters.get('city')
    listing_type = filters.get('listing_type')
    property_type = filters.get('property_type')
    bedrooms = filters.get('bedrooms')
    min_price = filters.get('min_price')
    max_price = filters.get('max_price')
    q = filters.get('q')

    if city:
        qs = qs.filter(city__name__iexact=city)
    if listing_type:
        qs = qs.filter(listing_type=listing_type)
    if property_type:
        qs = qs.filter(property_type=property_type)
    if bedrooms:
        qs = qs.filter(bedrooms=int(bedrooms))
    if min_price:
        qs = qs.filter(price__gte=min_price)
    if max_price:
        qs = qs.filter(price__lte=max_price)
    if q:
        qs = qs.filter(
            Q(title__icontains=q) |
            Q(locality__name__icontains=q) |
            Q(city__name__icontains=q) |
            Q(address__icontains=q)
        )
    return qs


def get_featured_properties(limit=6):
    qs = Property.objects.filter(
        status=PropertyStatus.ACTIVE, is_featured=True
    ).select_related('city', 'locality').prefetch_related('images')
    if qs.exists():
        return qs[:limit]
    # Fallback: any active listings so seeded data shows on home immediately
    return Property.objects.filter(
        status=PropertyStatus.ACTIVE
    ).select_related('city', 'locality').prefetch_related('images')[:limit]


def get_property_detail(slug):
    return Property.objects.select_related(
        'city', 'locality', 'broker', 'broker__profile'
    ).prefetch_related('images').get(slug=slug, status=PropertyStatus.ACTIVE)


def increment_views(property_id):
    Property.objects.filter(pk=property_id).update(views_count=F('views_count') + 1)


def create_inquiry(property_obj, data, user=None):
    inquiry = Inquiry(
        property=property_obj,
        name=data['name'],
        phone=data['phone'],
        email=data.get('email', ''),
        message=data.get('message', ''),
    )
    if user and user.is_authenticated:
        inquiry.client = user
    inquiry.save()
    return inquiry


def get_broker_properties(broker):
    return Property.objects.filter(broker=broker).select_related('city', 'locality').prefetch_related('images')


def toggle_saved_property(user, property_id):
    obj, created = SavedProperty.objects.get_or_create(user=user, property_id=property_id)
    if not created:
        obj.delete()
        return False
    return True


def get_client_saved_properties(user):
    return SavedProperty.objects.filter(user=user).select_related(
        'property', 'property__city', 'property__locality'
    ).prefetch_related('property__images')


def is_property_saved(user, property_id):
    if not user or not user.is_authenticated:
        return False
    return SavedProperty.objects.filter(user=user, property_id=property_id).exists()


def get_builder_projects(builder):
    return Project.objects.filter(builder=builder).select_related('city', 'locality')


def get_builder_project_stats(builder):
    qs = get_builder_projects(builder)
    total_units = sum(qs.values_list('total_units', flat=True))
    sold_units = sum(qs.values_list('sold_units', flat=True))
    return {
        'total': qs.count(),
        'ongoing': qs.filter(status=ProjectStatus.ONGOING).count(),
        'upcoming': qs.filter(status=ProjectStatus.UPCOMING).count(),
        'completed': qs.filter(status=ProjectStatus.COMPLETED).count(),
        'total_units': total_units,
        'sold_units': sold_units,
        'available_units': sum(qs.values_list('available_units', flat=True)),
    }


def get_city_stats(city):
    from users.models import User, Role
    from django.db.models import Q, Count
    props = Property.objects.filter(city=city)
    broker_qs = User.objects.filter(role=Role.BROKER).filter(
        Q(broker_properties__city=city) | Q(profile__city__iexact=city.name)
    ).distinct()
    return {
        'total_listings': props.count(),
        'active_listings': props.filter(status=PropertyStatus.ACTIVE).count(),
        'pending_listings': props.filter(status=PropertyStatus.PENDING).count(),
        'broker_count': broker_qs.count(),
        'pending_brokers': broker_qs.filter(is_verified=False, is_active=True).count(),
        'suspended_brokers': broker_qs.filter(is_active=False).count(),
    }


def get_city_pending_properties(city):
    return Property.objects.filter(
        city=city, status=PropertyStatus.PENDING
    ).select_related('broker', 'locality').order_by('-created_at')


def get_city_all_listings(city):
    return Property.objects.filter(city=city).select_related(
        'broker', 'locality'
    ).order_by('-created_at')


def get_city_brokers(city):
    from users.models import User, Role
    from django.db.models import Q, Count
    return User.objects.filter(role=Role.BROKER).filter(
        Q(broker_properties__city=city) | Q(profile__city__iexact=city.name)
    ).distinct().select_related('profile').annotate(
        city_listing_count=Count(
            'broker_properties',
            filter=Q(broker_properties__city=city),
            distinct=True,
        )
    )


def approve_property(pk):
    Property.objects.filter(pk=pk).update(status=PropertyStatus.ACTIVE)
    return Property.objects.select_related('broker', 'locality', 'city').get(pk=pk)


def reject_property(pk):
    Property.objects.filter(pk=pk).update(status=PropertyStatus.INACTIVE)
    return Property.objects.select_related('broker', 'locality', 'city').get(pk=pk)


def verify_broker(pk):
    from users.models import User
    User.objects.filter(pk=pk).update(is_verified=True)
    return User.objects.select_related('profile').get(pk=pk)


def suspend_broker(pk):
    from users.models import User
    User.objects.filter(pk=pk).update(is_active=False)
    return User.objects.select_related('profile').get(pk=pk)


def reinstate_broker(pk):
    from users.models import User
    User.objects.filter(pk=pk).update(is_active=True, is_verified=True)
    return User.objects.select_related('profile').get(pk=pk)


def get_broker_stats(broker):
    qs = get_broker_properties(broker)
    from .models import PropertyStatus
    return {
        'total': qs.count(),
        'active': qs.filter(status=PropertyStatus.ACTIVE).count(),
        'pending': qs.filter(status=PropertyStatus.PENDING).count(),
        'total_views': sum(qs.values_list('views_count', flat=True)),
        'total_inquiries': Inquiry.objects.filter(property__broker=broker).count(),
        'unread_inquiries': Inquiry.objects.filter(property__broker=broker, is_read=False).count(),
    }
