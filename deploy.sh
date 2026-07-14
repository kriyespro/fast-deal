#!/bin/bash
# PropSurat — Deploy / Update script
# Run on server: bash deploy.sh
set -e

echo "=== PropSurat Deploy ==="

# 1. Pull latest code
git pull origin main

# 2. Build + start containers (no downtime on rolling update)
docker compose --env-file .env.production up -d --build web

# 3. Restart nginx to pick up any config changes
docker compose restart nginx

# 4. Seed demo data if DB has no ACTIVE properties (safe / idempotent)
ACTIVE=$(docker compose exec -T web python manage.py shell -c "from properties.models import Property, PropertyStatus; print(Property.objects.filter(status=PropertyStatus.ACTIVE).count())" | tr -d '\r')
if [ "$ACTIVE" = "0" ]; then
  echo "=== No active properties — seeding demo data ==="
  docker compose exec -T web python manage.py seed_demo
fi

echo "=== Done. Site live at https://propsurat.com/ ==="
echo "Check: https://propsurat.com/listings/"
