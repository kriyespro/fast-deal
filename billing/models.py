from django.db import models
from django.conf import settings
from django.utils import timezone
import uuid


class PlanName(models.TextChoices):
    FREE = 'free', 'Free'
    BASIC = 'basic', 'Basic'
    PRO = 'pro', 'Pro'


class Plan(models.Model):
    name = models.CharField(max_length=20, choices=PlanName.choices, unique=True)
    display_name = models.CharField(max_length=50)
    price = models.DecimalField(max_digits=10, decimal_places=2, default=0)
    listing_limit = models.PositiveIntegerField(default=2, help_text='0 = unlimited')
    project_limit = models.PositiveIntegerField(default=1, help_text='0 = unlimited')
    features = models.TextField(blank=True, help_text='Comma-separated feature list')
    is_active = models.BooleanField(default=True)
    duration_days = models.PositiveIntegerField(default=30)

    class Meta:
        ordering = ['price']

    def __str__(self):
        return self.display_name

    @property
    def features_list(self):
        return [f.strip() for f in self.features.split(',') if f.strip()]

    @property
    def price_display(self):
        if self.price == 0:
            return 'Free'
        return f'₹{int(self.price):,}/mo'


class SubscriptionStatus(models.TextChoices):
    PENDING = 'pending', 'Pending Payment'
    ACTIVE = 'active', 'Active'
    EXPIRED = 'expired', 'Expired'
    CANCELLED = 'cancelled', 'Cancelled'


class Subscription(models.Model):
    user = models.OneToOneField(
        settings.AUTH_USER_MODEL,
        on_delete=models.CASCADE,
        related_name='subscription',
    )
    plan = models.ForeignKey(Plan, on_delete=models.PROTECT, related_name='subscriptions')
    status = models.CharField(
        max_length=20, choices=SubscriptionStatus.choices,
        default=SubscriptionStatus.PENDING
    )
    started_at = models.DateTimeField(null=True, blank=True)
    expires_at = models.DateTimeField(null=True, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        ordering = ['-created_at']

    def __str__(self):
        return f'{self.user.email} — {self.plan.display_name} ({self.status})'

    @property
    def is_active(self):
        if self.status != SubscriptionStatus.ACTIVE:
            return False
        if self.expires_at and timezone.now() > self.expires_at:
            return False
        return True

    @property
    def days_remaining(self):
        if not self.expires_at:
            return 0
        delta = self.expires_at - timezone.now()
        return max(0, delta.days)


class InvoiceStatus(models.TextChoices):
    PENDING = 'pending', 'Pending'
    PAID = 'paid', 'Paid'
    FAILED = 'failed', 'Failed'


class Invoice(models.Model):
    subscription = models.ForeignKey(
        Subscription, on_delete=models.CASCADE, related_name='invoices'
    )
    user = models.ForeignKey(
        settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='invoices'
    )
    plan = models.ForeignKey(Plan, on_delete=models.PROTECT)
    invoice_number = models.CharField(max_length=50, unique=True)
    amount = models.DecimalField(max_digits=10, decimal_places=2)
    status = models.CharField(
        max_length=20, choices=InvoiceStatus.choices,
        default=InvoiceStatus.PENDING
    )
    payment_method = models.CharField(max_length=50, default='Demo Payment')
    transaction_id = models.CharField(max_length=100, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    paid_at = models.DateTimeField(null=True, blank=True)

    class Meta:
        ordering = ['-created_at']

    def __str__(self):
        return f'INV-{self.invoice_number}'

    def save(self, *args, **kwargs):
        if not self.invoice_number:
            self.invoice_number = f'PS{timezone.now().strftime("%Y%m")}{str(uuid.uuid4())[:8].upper()}'
        super().save(*args, **kwargs)
