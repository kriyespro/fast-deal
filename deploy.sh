#!/bin/bash
# PropSurat — Deploy / Update script
# Run on server: bash deploy.sh
set -e

echo "=== PropSurat Deploy ==="

# 1. Pull latest code
git pull origin main

# 2. Build + start containers
docker compose --env-file .env.production up -d --build web

# Wait for web to be healthy
echo "Waiting for web container..."
sleep 5

# 3. Restart nginx to pick up any config changes
docker compose restart nginx 2>/dev/null || true

# 4. ALWAYS seed demo data (idempotent — safe to re-run)
echo "=== Seeding cities + active properties ==="
docker compose exec -T web python manage.py seed_demo

# 5. Verify
echo "=== Verify DB ==="
docker compose exec -T web python manage.py shell -c "
from properties.models import Property, City, PropertyStatus
print('cities=', City.objects.count())
print('properties=', Property.objects.count())
print('active=', Property.objects.filter(status=PropertyStatus.ACTIVE).count())
print('featured=', Property.objects.filter(status=PropertyStatus.ACTIVE, is_featured=True).count())
for p in Property.objects.filter(status=PropertyStatus.ACTIVE)[:5]:
    print(' -', p.pk, p.slug, p.title)
"

echo "=== Done. Hard-refresh https://propsurat.com/listings/ ==="
