"""
Compress existing large PropertyImage files (PNG→JPEG, resize, ~400KB max).
Run: python3 manage.py compress_images
"""
import io
import os
from pathlib import Path
from django.core.management.base import BaseCommand
from properties.models import PropertyImage


class Command(BaseCommand):
    help = 'Compress all PropertyImage files to JPEG ≤1200×900, ~400KB'

    def handle(self, *args, **options):
        from PIL import Image as PilImage

        compressed_dir = Path('media/properties/compressed')
        compressed_dir.mkdir(parents=True, exist_ok=True)

        for pi in PropertyImage.objects.all():
            if not pi.image:
                continue
            try:
                path = pi.image.path
            except (ValueError, FileNotFoundError):
                continue
            if not os.path.exists(path):
                self.stdout.write(self.style.WARNING(f'  missing: {pi.image.name}'))
                continue

            size_kb = os.path.getsize(path) // 1024
            old_name = os.path.splitext(os.path.basename(path))[0]
            new_rel = f'properties/compressed/{old_name}.jpg'
            new_abs = str(compressed_dir / f'{old_name}.jpg')

            # Skip if already compressed and up to date
            if os.path.exists(new_abs) and pi.image.name == new_rel:
                self.stdout.write(f'  skip [{pi.pk}] already compressed')
                continue

            try:
                img = PilImage.open(path)
                orig_format = img.format or 'unknown'
                if img.mode not in ('RGB', 'L'):
                    img = img.convert('RGB')
                img.thumbnail((1200, 900), PilImage.LANCZOS)
                buf = io.BytesIO()
                img.save(buf, format='JPEG', quality=82, optimize=True)
                new_kb = len(buf.getvalue()) // 1024

                with open(new_abs, 'wb') as f:
                    f.write(buf.getvalue())

                PropertyImage.objects.filter(pk=pi.pk).update(image=new_rel)
                self.stdout.write(
                    self.style.SUCCESS(
                        f'  ✓ [{pi.pk}] {orig_format} {size_kb}KB → JPEG {new_kb}KB  {old_name[:40]}'
                    )
                )
            except Exception as e:
                self.stdout.write(self.style.WARNING(f'  ! [{pi.pk}] failed: {e}'))

        self.stdout.write(self.style.SUCCESS('\nDone.'))
