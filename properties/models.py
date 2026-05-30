from django.db import models
from django.conf import settings


class City(models.Model):
    name = models.CharField(max_length=100, unique=True)
    state = models.CharField(max_length=100)
    is_active = models.BooleanField(default=True)

    class Meta:
        verbose_name_plural = 'Cities'
        ordering = ['name']

    def __str__(self):
        return self.name


class Locality(models.Model):
    city = models.ForeignKey(City, on_delete=models.CASCADE, related_name='localities')
    name = models.CharField(max_length=150)
    is_active = models.BooleanField(default=True)

    class Meta:
        verbose_name_plural = 'Localities'
        ordering = ['name']
        unique_together = ('city', 'name')

    def __str__(self):
        return f'{self.name}, {self.city.name}'


class PropertyType(models.TextChoices):
    APARTMENT = 'apartment', 'Apartment / Flat'
    HOUSE = 'house', 'Independent House / Villa'
    PLOT = 'plot', 'Plot / Land'
    COMMERCIAL_OFFICE = 'commercial_office', 'Commercial Office'
    COMMERCIAL_SHOP = 'commercial_shop', 'Commercial Shop'
    PG = 'pg', 'PG / Co-living'
    PROJECT = 'project', 'New Project'


class ListingType(models.TextChoices):
    SALE = 'sale', 'For Sale'
    RENT = 'rent', 'For Rent'
    PG = 'pg', 'PG / Co-living'


class PropertyStatus(models.TextChoices):
    DRAFT = 'draft', 'Draft'
    PENDING = 'pending', 'Pending Approval'
    ACTIVE = 'active', 'Active'
    SOLD = 'sold', 'Sold / Rented'
    INACTIVE = 'inactive', 'Inactive'


class Property(models.Model):
    # Ownership
    broker = models.ForeignKey(
        settings.AUTH_USER_MODEL, on_delete=models.SET_NULL,
        null=True, blank=True, related_name='broker_properties'
    )
    builder = models.ForeignKey(
        settings.AUTH_USER_MODEL, on_delete=models.SET_NULL,
        null=True, blank=True, related_name='builder_properties'
    )

    # Basic info
    title = models.CharField(max_length=200)
    slug = models.SlugField(max_length=220, unique=True, blank=True)
    description = models.TextField(blank=True)
    property_type = models.CharField(max_length=30, choices=PropertyType.choices, default=PropertyType.APARTMENT)
    listing_type = models.CharField(max_length=10, choices=ListingType.choices, default=ListingType.SALE)
    status = models.CharField(max_length=20, choices=PropertyStatus.choices, default=PropertyStatus.PENDING)

    # Location
    city = models.ForeignKey(City, on_delete=models.SET_NULL, null=True, related_name='properties')
    locality = models.ForeignKey(Locality, on_delete=models.SET_NULL, null=True, blank=True, related_name='properties')
    address = models.CharField(max_length=300, blank=True)
    pincode = models.CharField(max_length=10, blank=True)
    latitude = models.DecimalField(max_digits=9, decimal_places=6, null=True, blank=True)
    longitude = models.DecimalField(max_digits=9, decimal_places=6, null=True, blank=True)

    # Pricing
    price = models.DecimalField(max_digits=14, decimal_places=2)
    price_per_sqft = models.DecimalField(max_digits=10, decimal_places=2, null=True, blank=True)
    is_negotiable = models.BooleanField(default=False)

    # Size / specs
    area_sqft = models.PositiveIntegerField(null=True, blank=True)
    bedrooms = models.PositiveSmallIntegerField(null=True, blank=True)
    bathrooms = models.PositiveSmallIntegerField(null=True, blank=True)
    balconies = models.PositiveSmallIntegerField(null=True, blank=True)
    floor_number = models.PositiveSmallIntegerField(null=True, blank=True)
    total_floors = models.PositiveSmallIntegerField(null=True, blank=True)
    furnishing = models.CharField(
        max_length=20,
        choices=[('unfurnished', 'Unfurnished'), ('semi', 'Semi-Furnished'), ('furnished', 'Fully Furnished')],
        default='unfurnished'
    )

    # RERA
    rera_id = models.CharField(max_length=100, blank=True)
    is_rera_verified = models.BooleanField(default=False)

    # Features (JSON-ish stored as comma-separated)
    amenities = models.TextField(blank=True, help_text='Comma separated: Gym,Pool,Parking')

    # Meta
    is_featured = models.BooleanField(default=False)
    views_count = models.PositiveIntegerField(default=0)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        verbose_name_plural = 'Properties'
        ordering = ['-is_featured', '-created_at']

    def __str__(self):
        return self.title

    @property
    def price_display(self):
        p = float(self.price)
        if p >= 10_000_000:
            return f'₹{p / 10_000_000:.1f} Cr'
        elif p >= 100_000:
            return f'₹{p / 100_000:.1f} L'
        elif p >= 1000:
            return f'₹{p / 1000:.0f}K/mo'
        return f'₹{p:,.0f}'

    @property
    def amenities_list(self):
        return [a.strip() for a in self.amenities.split(',') if a.strip()]

    def save(self, *args, **kwargs):
        if not self.slug:
            from django.utils.text import slugify
            import uuid
            self.slug = slugify(self.title) + '-' + str(uuid.uuid4())[:8]
        super().save(*args, **kwargs)


class PropertyImage(models.Model):
    property = models.ForeignKey(Property, on_delete=models.CASCADE, related_name='images')
    image = models.ImageField(upload_to='properties/%Y/%m/')
    caption = models.CharField(max_length=200, blank=True)
    is_primary = models.BooleanField(default=False)
    order = models.PositiveSmallIntegerField(default=0)

    class Meta:
        ordering = ['order', '-is_primary']

    def __str__(self):
        return f'Image for {self.property.title}'

    def save(self, *args, **kwargs):
        super().save(*args, **kwargs)
        if self.image:
            self._compress()

    def _compress(self):
        import io, os
        from PIL import Image as PilImage
        from django.conf import settings

        path = self.image.path
        if not os.path.exists(path):
            return
        if path.lower().endswith(('.jpg', '.jpeg')) and os.path.getsize(path) < 400_000:
            return
        try:
            img = PilImage.open(path)
            if img.mode not in ('RGB', 'L'):
                img = img.convert('RGB')
            img.thumbnail((1200, 900), PilImage.LANCZOS)
            buf = io.BytesIO()
            img.save(buf, format='JPEG', quality=82, optimize=True)
            buf.seek(0)
            old_name = os.path.splitext(os.path.basename(self.image.name))[0]
            new_rel = f'properties/compressed/{old_name}.jpg'
            new_abs = os.path.join(settings.MEDIA_ROOT, new_rel)
            os.makedirs(os.path.dirname(new_abs), exist_ok=True)
            with open(new_abs, 'wb') as f:
                f.write(buf.getvalue())
            PropertyImage.objects.filter(pk=self.pk).update(image=new_rel)
            self.image.name = new_rel
        except Exception:
            pass


class ProjectType(models.TextChoices):
    RESIDENTIAL = 'residential', 'Residential'
    COMMERCIAL = 'commercial', 'Commercial'
    MIXED = 'mixed', 'Mixed Use'


class ProjectStatus(models.TextChoices):
    UPCOMING = 'upcoming', 'Upcoming'
    ONGOING = 'ongoing', 'Ongoing'
    COMPLETED = 'completed', 'Completed'


class Project(models.Model):
    builder = models.ForeignKey(
        settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name='projects'
    )
    name = models.CharField(max_length=200)
    description = models.TextField(blank=True)
    project_type = models.CharField(max_length=20, choices=ProjectType.choices, default=ProjectType.RESIDENTIAL)
    status = models.CharField(max_length=20, choices=ProjectStatus.choices, default=ProjectStatus.UPCOMING)
    city = models.ForeignKey(City, on_delete=models.SET_NULL, null=True, related_name='projects')
    locality = models.ForeignKey(Locality, on_delete=models.SET_NULL, null=True, blank=True, related_name='projects')
    address = models.CharField(max_length=300, blank=True)
    price_min = models.DecimalField(max_digits=14, decimal_places=2)
    price_max = models.DecimalField(max_digits=14, decimal_places=2, null=True, blank=True)
    total_units = models.PositiveIntegerField(default=0)
    sold_units = models.PositiveIntegerField(default=0)
    available_units = models.PositiveIntegerField(default=0)
    blocked_units = models.PositiveIntegerField(default=0)
    completion_pct = models.PositiveSmallIntegerField(default=0, help_text='0–100%')
    rera_id = models.CharField(max_length=100, blank=True)
    is_rera_verified = models.BooleanField(default=False)
    expected_possession = models.DateField(null=True, blank=True)
    image = models.ImageField(upload_to='projects/%Y/%m/', blank=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        ordering = ['-created_at']

    def __str__(self):
        return self.name

    @property
    def price_display(self):
        p = float(self.price_min)
        def fmt(v):
            if v >= 10_000_000:
                return f'₹{v/10_000_000:.1f} Cr'
            elif v >= 100_000:
                return f'₹{v/100_000:.1f} L'
            return f'₹{v:,.0f}'
        if self.price_max and float(self.price_max) > p:
            return f'{fmt(p)} – {fmt(float(self.price_max))}'
        return fmt(p)

    @property
    def sold_pct(self):
        if not self.total_units:
            return 0
        return min(int(self.sold_units * 100 / self.total_units), 100)


class SavedProperty(models.Model):
    user = models.ForeignKey(
        settings.AUTH_USER_MODEL, on_delete=models.CASCADE,
        related_name='saved_properties'
    )
    property = models.ForeignKey(Property, on_delete=models.CASCADE, related_name='saved_by')
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        unique_together = ('user', 'property')
        ordering = ['-created_at']

    def __str__(self):
        return f'{self.user.email} saved {self.property.title}'


class Inquiry(models.Model):
    property = models.ForeignKey(Property, on_delete=models.CASCADE, related_name='inquiries')
    client = models.ForeignKey(
        settings.AUTH_USER_MODEL, on_delete=models.SET_NULL,
        null=True, blank=True, related_name='inquiries'
    )
    name = models.CharField(max_length=150)
    phone = models.CharField(max_length=15)
    email = models.EmailField(blank=True)
    message = models.TextField(blank=True)
    is_read = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        verbose_name_plural = 'Inquiries'
        ordering = ['-created_at']

    def __str__(self):
        return f'Inquiry from {self.name} on {self.property.title}'
