"""
Seed multiple demo images onto properties.

Uses bundled fixtures/demo_images/ first (works offline / on Docker).
Falls back to Unsplash/Picsum download if a file is missing.

  python manage.py seed_images
  python manage.py seed_images --force   # replace existing images
"""
import ssl
import urllib.request
from pathlib import Path

from django.core.files import File
from django.core.management.base import BaseCommand
from django.conf import settings

from properties.models import Property, PropertyImage


# Each key matches title or locality; values = list of local fixture filenames
# (files live in fixtures/demo_images/)
PROPERTY_IMAGE_SETS = {
    'Vesu': [
        ('1_1560448204-e.jpg', 'Spacious living room'),
        ('1_152270832359.jpg', 'Master bedroom'),
        ('1_1556909114-f.jpg', 'Modern kitchen'),
        ('7_1560448204-e.jpg', 'Another living angle'),
    ],
    'Athwa': [
        ('2_152277173984.jpg', 'Furnished living area'),
        ('2_150200522976.jpg', 'Bedroom view'),
        ('2_148415421896.jpg', 'Kitchen'),
        ('4_152277173984.jpg', 'Dining space'),
    ],
    'Piplod': [
        ('3_160058515434.jpg', 'Villa exterior'),
        ('3_160056675308.jpg', 'Villa interior'),
        ('7_1556909114-f.jpg', 'Kitchen'),
        ('5_161349049357.jpg', 'Modern apartment feel'),
    ],
    'Adajan': [
        ('4_152277173984.jpg', 'Living room'),
        ('4_1556909114-f.jpg', 'Kitchen'),
        ('1_152270832359.jpg', 'Bedroom'),
    ],
    'Pal': [
        ('5_161349049357.jpg', 'Modern apartment'),
        ('5_152270832359.jpg', 'Bedroom'),
        ('7_152270832359.jpg', 'Living area'),
        ('2_150200522976.jpg', 'Balcony room'),
    ],
    'Althan': [
        ('6_150038201746.jpg', 'Plot overview'),
        ('6_148632521202.jpg', 'Plot boundary'),
        ('3_160058515434.jpg', 'Surroundings'),
    ],
    'City Light': [
        ('7_1560448204-e.jpg', 'Premium apartment view'),
        ('7_152270832359.jpg', 'Living area'),
        ('7_1556909114-f.jpg', 'Kitchen'),
        ('1_152270832359.jpg', 'Master bedroom'),
    ],
    'VIP Road': [
        ('8_149736621654.jpg', 'Office floor'),
        ('8_149736681135.jpg', 'Meeting room'),
        ('5_161349049357.jpg', 'Workstations look'),
        ('2_152277173984.jpg', 'Reception lounge'),
    ],
}

# Fallback pool if locality not matched — still attach multiple images
DEFAULT_POOL = [
    ('1_1560448204-e.jpg', 'Living room'),
    ('1_152270832359.jpg', 'Bedroom'),
    ('1_1556909114-f.jpg', 'Kitchen'),
    ('5_161349049357.jpg', 'Exterior / view'),
]

class Command(BaseCommand):
    help = 'Attach multiple demo images to properties (local fixtures preferred)'

    def add_arguments(self, parser):
        parser.add_argument(
            '--force',
            action='store_true',
            help='Delete existing images and re-seed',
        )

    def handle(self, *args, **options):
        force = options['force']
        fixture_dir = Path(settings.BASE_DIR) / 'fixtures' / 'demo_images'
        fixture_dir.mkdir(parents=True, exist_ok=True)

        total_props = 0
        total_imgs = 0

        for prop in Property.objects.select_related('locality').all():
            existing = prop.images.count()
            if existing and not force:
                self.stdout.write(f'  skip {prop.title[:45]} ({existing} images)')
                continue

            if force and existing:
                prop.images.all().delete()
                self.stdout.write(f'  cleared {existing} images for {prop.title[:40]}')

            image_set = self._match_set(prop)
            added = 0
            for idx, (filename, caption) in enumerate(image_set):
                path = fixture_dir / filename
                if not path.exists():
                    ok = self._download_fallback(path, filename)
                    if not ok:
                        self.stdout.write(self.style.WARNING(f'    ! missing {filename}'))
                        continue
                try:
                    with open(path, 'rb') as f:
                        pi = PropertyImage(
                            property=prop,
                            caption=caption,
                            is_primary=(idx == 0),
                            order=idx,
                        )
                        pi.image.save(f'{prop.pk}_{idx}_{filename}', File(f), save=True)
                    added += 1
                except Exception as e:
                    self.stdout.write(self.style.WARNING(f'    ! {filename}: {e}'))

            if added:
                total_props += 1
                total_imgs += added
                self.stdout.write(self.style.SUCCESS(f'  ✓ {prop.title[:45]} — {added} images'))
            else:
                self.stdout.write(self.style.WARNING(f'  ✗ no images for {prop.title[:45]}'))

        self.stdout.write(self.style.SUCCESS(
            f'\nDone. Updated {total_props} properties with {total_imgs} images.'
        ))

    def _match_set(self, prop):
        for key, items in PROPERTY_IMAGE_SETS.items():
            if key in prop.title or (prop.locality and key in prop.locality.name):
                return items
        return DEFAULT_POOL

    def _download_fallback(self, dest_path, filename):
        """Last resort: picsum so live seed never stays empty."""
        seed = abs(hash(filename)) % 10000
        url = f'https://picsum.photos/seed/propsurat{seed}/900/600.jpg'
        headers = {'User-Agent': 'Mozilla/5.0 (compatible; PropSurat/1.0)'}
        ssl_ctx = ssl.create_default_context()
        ssl_ctx.check_hostname = False
        ssl_ctx.verify_mode = ssl.CERT_NONE
        try:
            req = urllib.request.Request(url, headers=headers)
            with urllib.request.urlopen(req, timeout=20, context=ssl_ctx) as resp:
                dest_path.write_bytes(resp.read())
            return dest_path.exists() and dest_path.stat().st_size > 1000
        except Exception as e:
            self.stdout.write(self.style.WARNING(f'    download fail {filename}: {e}'))
            return False
