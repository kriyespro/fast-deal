from django.utils import timezone
from datetime import timedelta

from .models import Plan, Subscription, Invoice, SubscriptionStatus, InvoiceStatus, PlanName


def get_all_plans():
    return Plan.objects.filter(is_active=True).order_by('price')


def get_user_subscription(user):
    try:
        return user.subscription
    except Subscription.DoesNotExist:
        return None


def get_user_active_plan(user):
    sub = get_user_subscription(user)
    if sub and sub.is_active:
        return sub.plan
    try:
        return Plan.objects.get(name=PlanName.FREE)
    except Plan.DoesNotExist:
        return None


def get_listing_limit(user):
    plan = get_user_active_plan(user)
    if not plan:
        return 2
    return plan.listing_limit


def get_project_limit(user):
    plan = get_user_active_plan(user)
    if not plan:
        return 1
    return plan.project_limit


def can_add_listing(user):
    from properties.models import Property, PropertyStatus
    limit = get_listing_limit(user)
    if limit == 0:
        return True, None
    current = Property.objects.filter(broker=user).exclude(
        status=PropertyStatus.INACTIVE
    ).count()
    if current >= limit:
        return False, limit
    return True, None


def can_add_project(user):
    from properties.models import Project
    limit = get_project_limit(user)
    if limit == 0:
        return True, None
    current = Project.objects.filter(builder=user).count()
    if current >= limit:
        return False, limit
    return True, None


def create_pending_subscription(user, plan):
    existing = get_user_subscription(user)
    if existing:
        existing.plan = plan
        existing.status = SubscriptionStatus.PENDING
        existing.save(update_fields=['plan', 'status', 'updated_at'])
        return existing
    return Subscription.objects.create(user=user, plan=plan)


def activate_subscription(subscription):
    now = timezone.now()
    subscription.status = SubscriptionStatus.ACTIVE
    subscription.started_at = now
    subscription.expires_at = now + timedelta(days=subscription.plan.duration_days)
    subscription.save(update_fields=['status', 'started_at', 'expires_at', 'updated_at'])
    return subscription


def create_invoice(subscription, payment_method='Demo Payment'):
    import uuid
    invoice = Invoice.objects.create(
        subscription=subscription,
        user=subscription.user,
        plan=subscription.plan,
        amount=subscription.plan.price,
        status=InvoiceStatus.PAID,
        payment_method=payment_method,
        transaction_id=f'DEMO-{str(uuid.uuid4())[:12].upper()}',
        paid_at=timezone.now(),
    )
    return invoice


def process_demo_payment(subscription_id):
    sub = Subscription.objects.select_related('plan', 'user').get(pk=subscription_id)
    activate_subscription(sub)
    invoice = create_invoice(sub)
    return sub, invoice


def get_user_invoices(user):
    return Invoice.objects.filter(user=user).select_related('plan').order_by('-created_at')
