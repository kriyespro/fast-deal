from django.db.models import Q
from django.utils import timezone


def get_platform_stats():
    from users.models import User, Role
    from properties.models import Property, PropertyStatus

    today = timezone.now().date()
    users = User.objects
    props = Property.objects

    return {
        'total_users': users.count(),
        'signups_today': users.filter(date_joined__date=today).count(),
        'total_brokers': users.filter(role=Role.BROKER, is_active=True).count(),
        'total_builders': users.filter(role=Role.BUILDER, is_active=True).count(),
        'total_clients': users.filter(role=Role.CLIENT).count(),
        'banned_users': users.filter(is_active=False).count(),
        'total_properties': props.count(),
        'active_properties': props.filter(status=PropertyStatus.ACTIVE).count(),
        'pending_properties': props.filter(status=PropertyStatus.PENDING).count(),
    }


def get_users_list(q=None, role=None, limit=50):
    from users.models import User
    qs = User.objects.select_related('profile').order_by('-date_joined')
    if q:
        qs = qs.filter(Q(full_name__icontains=q) | Q(email__icontains=q))
    if role:
        qs = qs.filter(role=role)
    return qs[:limit]


def get_recent_admin_logs(limit=30):
    from .models import AdminLog
    return AdminLog.objects.select_related('admin', 'target_user').order_by('-created_at')[:limit]


def log_admin_action(admin, action, target_user=None, details='', ip=''):
    from .models import AdminLog
    AdminLog.objects.create(
        admin=admin,
        action=action,
        target_user=target_user,
        details=details,
        ip_address=ip,
    )


def get_client_ip(request):
    x_forwarded = request.META.get('HTTP_X_FORWARDED_FOR')
    if x_forwarded:
        return x_forwarded.split(',')[0].strip()
    return request.META.get('REMOTE_ADDR', '')
