#!/bin/bash
# PropSurat — Deploy to the LIVE stack (project name: propsurat → propsurat-web-1 on :8882)
# Run from ~/fast-deal: bash deploy.sh
set -e

cd "$(dirname "$0")"
ENV_FILE=".env.production"
PROJECT="propsurat"

if [ ! -f "$ENV_FILE" ]; then
  echo "ERROR: $ENV_FILE missing"
  exit 1
fi

ln -sfn .env.production .env

echo "=== PropSurat Deploy (compose project: $PROJECT) ==="
git pull origin main

# Live site is propsurat-web-1 on 8882 — free the port if an old sibling container holds it
# (only stop the web image that owns 8882; keep propsurat-db/redis)
if docker ps --format '{{.Names}} {{.Ports}}' | grep -q 'propsurat-web-1.*8882'; then
  echo "=== Stopping old propsurat-web-1 so we can rebuild on :8882 ==="
  docker stop propsurat-web-1 || true
  docker rm propsurat-web-1 || true
fi

# Also remove accidental fast-deal-web if present (wrong project name)
docker rm -f fast-deal-web-1 2>/dev/null || true

echo "=== Build + start LIVE stack ==="
docker compose -p "$PROJECT" --env-file "$ENV_FILE" up -d --build web db redis

echo "Waiting for web..."
sleep 10

echo "=== Seed into LIVE DB (propsurat-db) ==="
docker compose -p "$PROJECT" --env-file "$ENV_FILE" exec -T web python manage.py migrate --noinput
docker compose -p "$PROJECT" --env-file "$ENV_FILE" exec -T web python manage.py seed_demo
docker compose -p "$PROJECT" --env-file "$ENV_FILE" exec -T web python manage.py seed_images --force
docker compose -p "$PROJECT" --env-file "$ENV_FILE" exec -T web python manage.py check_properties

if command -v nginx >/dev/null 2>&1; then
  echo "=== Reloading host nginx ==="
  nginx -t && systemctl reload nginx || nginx -s reload || true
fi

echo "=== Smoke test (nginx upstream) ==="
# Must send Host: propsurat.com — curl to 127.0.0.1 gets DisallowedHost 400 otherwise
SMOKE_OPTS=(-sS -H "Host: propsurat.com" -H "X-Forwarded-Proto: https")
curl "${SMOKE_OPTS[@]}" -o /dev/null -w "8882 home → %{http_code}\n" http://127.0.0.1:8882/ || true
curl "${SMOKE_OPTS[@]}" http://127.0.0.1:8882/listings/ | grep -oE '[0-9]+ properties mile|Koi property|Koramangala' | head -5 || true
SLUG=$(docker compose -p "$PROJECT" --env-file "$ENV_FILE" exec -T web python manage.py shell -c \
  "from properties.models import Property, PropertyStatus; p=Property.objects.filter(status=PropertyStatus.ACTIVE).first(); print(p.slug if p else '')" | tr -d '\r')
if [ -n "$SLUG" ]; then
  curl "${SMOKE_OPTS[@]}" -o /dev/null -w "8882 detail → %{http_code}\n" "http://127.0.0.1:8882/property/${SLUG}/" || true
fi

echo "=== Done. Hard-refresh https://propsurat.com/listings/ ==="
echo "Containers should include: propsurat-web-1 on 0.0.0.0:8882→8000"
