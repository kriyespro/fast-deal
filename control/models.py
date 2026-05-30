from django.db import models
from django.conf import settings


class AdminLog(models.Model):
    ACTION_CHOICES = [
        ('ban_user', 'Ban User'),
        ('unban_user', 'Unban User'),
        ('impersonate', 'Impersonate User'),
        ('stop_impersonate', 'Stop Impersonation'),
        ('approve_listing', 'Approve Listing'),
        ('reject_listing', 'Reject Listing'),
        ('verify_broker', 'Verify Broker'),
        ('suspend_broker', 'Suspend Broker'),
        ('reinstate_broker', 'Reinstate Broker'),
        ('other', 'Other'),
    ]

    admin = models.ForeignKey(
        settings.AUTH_USER_MODEL,
        on_delete=models.SET_NULL,
        null=True,
        related_name='admin_actions',
    )
    action = models.CharField(max_length=50, choices=ACTION_CHOICES)
    target_user = models.ForeignKey(
        settings.AUTH_USER_MODEL,
        on_delete=models.SET_NULL,
        null=True, blank=True,
        related_name='admin_logs',
    )
    details = models.TextField(blank=True)
    ip_address = models.CharField(max_length=45, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        ordering = ['-created_at']
        verbose_name = 'Admin Log'

    def __str__(self):
        return f'{self.admin} → {self.action}'
