from django.contrib import admin
from django.utils.html import format_html
from .models import City, Locality, Property, PropertyImage, Inquiry


@admin.register(City)
class CityAdmin(admin.ModelAdmin):
    list_display = ('name', 'state', 'is_active')
    search_fields = ('name', 'state')


@admin.register(Locality)
class LocalityAdmin(admin.ModelAdmin):
    list_display = ('name', 'city', 'is_active')
    list_filter = ('city',)
    search_fields = ('name',)


class PropertyImageInline(admin.TabularInline):
    model = PropertyImage
    extra = 1
    fields = ('preview', 'image', 'caption', 'is_primary', 'order')
    readonly_fields = ('preview',)

    def preview(self, obj):
        if obj.pk and obj.image:
            return format_html('<img src="{}" style="height:70px;width:100px;object-fit:cover;border-radius:4px">', obj.image.url)
        return '—'
    preview.short_description = 'Preview'


@admin.register(Property)
class PropertyAdmin(admin.ModelAdmin):
    list_display = ('title', 'city', 'listing_type', 'property_type', 'price_display', 'status', 'is_featured', 'created_at')
    list_filter = ('status', 'listing_type', 'property_type', 'city', 'is_featured')
    search_fields = ('title', 'address', 'rera_id')
    inlines = [PropertyImageInline]
    readonly_fields = ('views_count', 'created_at', 'updated_at', 'slug')
    list_editable = ('status', 'is_featured')


@admin.register(Inquiry)
class InquiryAdmin(admin.ModelAdmin):
    list_display = ('name', 'phone', 'property', 'is_read', 'created_at')
    list_filter = ('is_read',)
    search_fields = ('name', 'phone', 'email')
