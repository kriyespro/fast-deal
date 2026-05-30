"""
Management command: seed demo property images from Unsplash.
Run: python3 manage.py seed_images
"""
import os
import ssl
import urllib.request
from pathlib import Path
from django.core.management.base import BaseCommand
from django.core.files import File
from properties.models import Property, PropertyImage

# Map property title keywords → list of (unsplash_photo_id, caption)
PROPERTY_IMAGES = {
    'Koramangala': [
        ('1560448204-e02f11c3d0e2', 'Spacious living room'),
        ('1522708323590-d24dbb6b0267', 'Master bedroom'),
        ('1556909114-f6e7ad7d3136', 'Modern kitchen'),
    ],
    'Bandra West': [
        ('1522771739844-6a9f6d5f14af', 'Furnished living area'),
        ('1502005229762-cf1b2da7c5d6', 'Bedroom view'),
        ('1484154218962-a197022b5858', 'Kitchen'),
    ],
    'Jubilee Hills': [
        ('1600585154340-be6161a56a0c', 'Villa exterior'),
        ('1600607687920-4e03d67f62cf', 'Private pool area'),
        ('1600566753086-00f18fb6b3ea', 'Villa interior'),
    ],
    'Adajan': [
        ('1522771739844-6a9f6d5f14af', 'Living room'),
        ('1556909114-f6e7ad7d3136', 'Kitchen'),
    ],
    'Whitefield': [
        ('1613490493576-7fde63acd811', 'Modern apartment'),
        ('1600607687920-4e03d67f62cf', 'Balcony view'),
        ('1522708323590-d24dbb6b0267', 'Bedroom'),
    ],
    'Baner': [
        ('1500382017468-9049fed747ef', 'Plot overview'),
        ('1486325212027-8081e485255e', 'Plot boundary'),
    ],
    'Powai': [
        ('1571979756952-2e48fec82e66', 'Premium apartment view'),
        ('1560448204-e02f11c3d0e2', 'Living area'),
        ('1522708323590-d24dbb6b0267', 'Master bedroom'),
        ('1556909114-f6e7ad7d3136', 'Kitchen'),
    ],
    'Hitech City': [
        ('1497366216548-37526070297c', 'Office floor'),
        ('1497366754035-f200a6f0b872', 'Meeting room'),
        ('1497366811353-6870744d04b2', 'Workstations'),
    ],
}

UNSPLASH_BASE = 'https://images.unsplash.com/photo-{id}?w=900&h=600&fit=crop&q=82&fm=jpg&cs=srgb'


class Command(BaseCommand):
    help = 'Download and seed demo property images from Unsplash'

    def handle(self, *args, **options):
        media_dir = Path('media/properties/seed')
        media_dir.mkdir(parents=True, exist_ok=True)

        headers = {
            'User-Agent': 'Mozilla/5.0 (compatible; PropSurat/1.0)',
            'Accept': 'image/webp,image/jpeg,image/*',
        }

        for prop in Property.objects.all():
            if prop.images.exists():
                self.stdout.write(f'  skip {prop.title} (already has images)')
                continue

            images_added = 0
            matched_key = None
            for key in PROPERTY_IMAGES:
                if key in prop.title or (prop.locality and key in prop.locality.name):
                    matched_key = key
                    break

            if not matched_key:
                self.stdout.write(self.style.WARNING(f'  no match for: {prop.title}'))
                continue

            for idx, (photo_id, caption) in enumerate(PROPERTY_IMAGES[matched_key]):
                url = UNSPLASH_BASE.format(id=photo_id)
                filename = f'{prop.pk}_{photo_id[:12]}.jpg'
                dest_path = media_dir / filename

                ssl_ctx = ssl.create_default_context()
                ssl_ctx.check_hostname = False
                ssl_ctx.verify_mode = ssl.CERT_NONE
                try:
                    req = urllib.request.Request(url, headers=headers)
                    with urllib.request.urlopen(req, timeout=15, context=ssl_ctx) as response:
                        with open(dest_path, 'wb') as f:
                            f.write(response.read())

                    with open(dest_path, 'rb') as f:
                        pi = PropertyImage(
                            property=prop,
                            caption=caption,
                            is_primary=(idx == 0),
                            order=idx,
                        )
                        pi.image.save(filename, File(f), save=True)
                    images_added += 1
                    self.stdout.write(f'    + img {idx + 1} for {prop.title[:40]}')
                except Exception as e:
                    self.stdout.write(self.style.WARNING(f'    ! failed {photo_id}: {e}'))

            if images_added:
                self.stdout.write(self.style.SUCCESS(f'  ✓ {prop.title} — {images_added} images'))

        self.stdout.write(self.style.SUCCESS('\nDone! Run the server to see images.'))
