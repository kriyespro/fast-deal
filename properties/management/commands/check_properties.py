"""
Quick diagnostic for live/prod — counts + sample property URLs.

  docker compose exec web python manage.py check_properties
"""
from django.core.management.base import BaseCommand
from django.urls import reverse

from properties.models import City, Property, PropertyStatus


class Command(BaseCommand):
    help = 'Print property/city counts and sample detail URLs'

    def handle(self, *args, **options):
        self.stdout.write(f'cities={City.objects.count()}')
        self.stdout.write(f'total={Property.objects.count()}')
        self.stdout.write(f'active={Property.objects.filter(status=PropertyStatus.ACTIVE).count()}')
        self.stdout.write(f'featured={Property.objects.filter(status=PropertyStatus.ACTIVE, is_featured=True).count()}')
        self.stdout.write(f'pending={Property.objects.filter(status=PropertyStatus.PENDING).count()}')
        for p in Property.objects.all()[:10]:
            try:
                path = reverse('property_detail', kwargs={'slug': p.slug}) if p.slug else '(no slug)'
            except Exception as e:
                path = f'(url error: {e})'
            self.stdout.write(
                f'  [{p.status}] pk={p.pk} featured={p.is_featured} {path} — {p.title}'
            )
