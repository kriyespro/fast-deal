from django.db.models import Q, F, Prefetch, Count, Sum
from django.core.cache import cache

from .models import Property, PropertyStatus, PropertyImage, Inquiry, SavedProperty, Project, ProjectStatus, City, Locality

SURAT_CITY_NAME = 'Surat'


def _attach_primary(qs):
    """Prefetch images ordered so templates can take [0] without extra queries."""
    return qs.prefetch_related(
        Prefetch(
            'images',
            queryset=PropertyImage.objects.order_by('-is_primary', 'order', 'pk'),
            to_attr='prefetched_images',
        )
    )


def get_active_properties(filters=None, list_view=True):
    qs = Property.objects.filter(status=PropertyStatus.ACTIVE).select_related(
        'city', 'locality', 'broker'
    )
    # PropSurat is Surat-only — public listings always scoped to Surat
    qs = qs.filter(city__name__iexact=SURAT_CITY_NAME)
    if list_view:
        qs = _attach_primary(qs)
    else:
        qs = qs.prefetch_related('images')

    if not filters:
        return qs

    city = filters.get('city')
    locality = filters.get('locality')
    listing_type = filters.get('listing_type')
    property_type = filters.get('property_type')
    bedrooms = filters.get('bedrooms')
    min_price = filters.get('min_price')
    max_price = filters.get('max_price')
    q = filters.get('q')

    if city and city.lower() != SURAT_CITY_NAME.lower():
        # Ignore other cities — portal is Surat-only
        pass
    if locality:
        qs = qs.filter(locality__name__iexact=locality)
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
    cache_key = f'featured_prop_ids_surat_v1_{limit}'
    ids = cache.get(cache_key)
    if ids is None:
        featured_ids = list(
            Property.objects.filter(
                status=PropertyStatus.ACTIVE,
                is_featured=True,
                city__name__iexact=SURAT_CITY_NAME,
            )
            .order_by('-created_at')
            .values_list('pk', flat=True)[:limit]
        )
        if len(featured_ids) < limit:
            extra = list(
                Property.objects.filter(
                    status=PropertyStatus.ACTIVE,
                    city__name__iexact=SURAT_CITY_NAME,
                )
                .exclude(pk__in=featured_ids)
                .order_by('-created_at')
                .values_list('pk', flat=True)[: limit - len(featured_ids)]
            )
            featured_ids.extend(extra)
        ids = featured_ids
        cache.set(cache_key, ids, 60 * 5)

    if not ids:
        return []

    qs = Property.objects.filter(pk__in=ids).select_related('city', 'locality')
    qs = _attach_primary(qs)
    by_id = {p.pk: p for p in qs}
    return [by_id[i] for i in ids if i in by_id]


def get_active_cities():
    cache_key = 'active_cities_v1'
    cities = cache.get(cache_key)
    if cities is None:
        cities = list(City.objects.filter(is_active=True).only('id', 'name', 'state'))
        cache.set(cache_key, cities, 60 * 30)
    return cities


def get_surat_localities():
    cache_key = 'surat_localities_v1'
    locs = cache.get(cache_key)
    if locs is None:
        locs = list(
            Locality.objects.filter(
                is_active=True, city__name__iexact=SURAT_CITY_NAME
            ).order_by('name').only('id', 'name')
        )
        cache.set(cache_key, locs, 60 * 30)
    return locs


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
    return _attach_primary(
        Property.objects.filter(broker=broker).select_related('city', 'locality')
        .annotate(lead_count=Count('inquiries', distinct=True))
    )


def create_property_with_images(broker, cleaned_data, image_files):
    """Create pending property + multiple images. First file = primary."""
    from django.db import transaction

    with transaction.atomic():
        prop = Property(broker=broker, status=PropertyStatus.PENDING, **cleaned_data)
        prop.save()
        added = 0
        for idx, f in enumerate(image_files):
            if not f:
                continue
            PropertyImage.objects.create(
                property=prop,
                image=f,
                is_primary=(idx == 0),
                order=idx,
            )
            added += 1
        cache.delete_many([f'featured_prop_ids_v2_{n}' for n in (5, 6, 8)])
        return prop, added


def add_property_images(prop, image_files):
    start = prop.images.count()
    added = 0
    for idx, f in enumerate(image_files):
        if not f:
            continue
        PropertyImage.objects.create(
            property=prop,
            image=f,
            is_primary=(start == 0 and idx == 0),
            order=start + idx,
        )
        added += 1
    return added


def toggle_saved_property(user, property_id):
    obj, created = SavedProperty.objects.get_or_create(user=user, property_id=property_id)
    if not created:
        obj.delete()
        return False
    return True


def get_client_saved_properties(user):
    return SavedProperty.objects.filter(user=user).select_related(
        'property', 'property__city', 'property__locality'
    ).prefetch_related(
        Prefetch(
            'property__images',
            queryset=PropertyImage.objects.order_by('-is_primary', 'order', 'pk'),
            to_attr='prefetched_images',
        )
    )

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
    cache.delete_many([f'featured_prop_ids_v2_{n}' for n in (5, 6, 8)])
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
    qs = Property.objects.filter(broker=broker)
    agg = qs.aggregate(
        total=Count('id'),
        active=Count('id', filter=Q(status=PropertyStatus.ACTIVE)),
        pending=Count('id', filter=Q(status=PropertyStatus.PENDING)),
        total_views=Sum('views_count'),
    )
    inq = Inquiry.objects.filter(property__broker=broker).aggregate(
        total=Count('id'),
        unread=Count('id', filter=Q(is_read=False)),
    )
    return {
        'total': agg['total'] or 0,
        'active': agg['active'] or 0,
        'pending': agg['pending'] or 0,
        'total_views': agg['total_views'] or 0,
        'total_inquiries': inq['total'] or 0,
        'unread_inquiries': inq['unread'] or 0,
    }
