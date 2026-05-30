from django.contrib.auth.models import AbstractBaseUser, BaseUserManager, PermissionsMixin
from django.db import models
from django.db.models.signals import post_save
from django.dispatch import receiver


class Role(models.TextChoices):
    CLIENT = 'client', 'Client'
    BROKER = 'broker', 'Broker'
    BUILDER = 'builder', 'Builder'
    CITY_ADMIN = 'city_admin', 'City Admin'
    GLOBAL_ADMIN = 'global_admin', 'Global Admin'


class UserManager(BaseUserManager):
    def create_user(self, email, password=None, **extra):
        if not email:
            raise ValueError('Email required')
        email = self.normalize_email(email)
        user = self.model(email=email, **extra)
        user.set_password(password)
        user.save(using=self._db)
        return user

    def create_superuser(self, email, password=None, **extra):
        extra.setdefault('role', Role.GLOBAL_ADMIN)
        extra.setdefault('is_staff', True)
        extra.setdefault('is_superuser', True)
        return self.create_user(email, password, **extra)


class User(AbstractBaseUser, PermissionsMixin):
    email = models.EmailField(unique=True)
    full_name = models.CharField(max_length=150)
    role = models.CharField(max_length=20, choices=Role.choices, default=Role.CLIENT)
    phone = models.CharField(max_length=15, blank=True)
    is_active = models.BooleanField(default=True)
    is_verified = models.BooleanField(default=False)
    is_staff = models.BooleanField(default=False)
    date_joined = models.DateTimeField(auto_now_add=True)

    USERNAME_FIELD = 'email'
    REQUIRED_FIELDS = ['full_name']

    objects = UserManager()

    class Meta:
        verbose_name = 'User'
        verbose_name_plural = 'Users'

    def __str__(self):
        return f'{self.full_name} <{self.email}>'

    @property
    def is_broker(self):
        return self.role == Role.BROKER

    @property
    def is_builder(self):
        return self.role == Role.BUILDER

    @property
    def is_city_admin(self):
        return self.role == Role.CITY_ADMIN

    @property
    def is_global_admin(self):
        return self.role == Role.GLOBAL_ADMIN


class Profile(models.Model):
    user = models.OneToOneField(User, on_delete=models.CASCADE, related_name='profile')
    avatar = models.ImageField(upload_to='avatars/', blank=True)
    city = models.CharField(max_length=100, blank=True)
    assigned_city = models.ForeignKey(
        'properties.City',
        null=True, blank=True,
        on_delete=models.SET_NULL,
        related_name='city_admins',
    )
    bio = models.TextField(blank=True)
    rera_number = models.CharField(max_length=50, blank=True)
    company_name = models.CharField(max_length=150, blank=True)
    website = models.URLField(blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    def __str__(self):
        return f'Profile: {self.user.email}'


@receiver(post_save, sender=User)
def create_user_profile(sender, instance, created, **kwargs):
    if created:
        Profile.objects.get_or_create(user=instance)
