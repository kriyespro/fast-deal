#!/bin/bash
# PropSurat — Deploy / Update script
# Run on server from ~/fast-deal (or /srv/propsurat): bash deploy.sh
set -e

cd "$(dirname "$0")"
ENV_FILE=".env.production"

if [ ! -f "$ENV_FILE" ]; then
  echo "ERROR: $ENV_FILE missing"
  exit 1
fi

# So docker compose variable substitution works without --env-file every time
ln -sfn .env.production .env

echo "=== PropSurat Deploy ==="
git pull origin main

echo "=== Build + start (publishes 127.0.0.1:8882 → gunicorn) ==="
docker compose --env-file "$ENV_FILE" up -d --build web db redis

echo "Waiting for web..."
sleep 8

echo "=== Seed demo properties ==="
docker compose --env-file "$ENV_FILE" exec -T web python manage.py seed_demo
docker compose --env-file "$ENV_FILE" exec -T web python manage.py check_properties

# Host nginx (not a compose service) — reload if present
if command -v nginx >/dev/null 2>&1; then
  echo "=== Reloading host nginx ==="
  nginx -t && systemctl reload nginx || nginx -s reload || true
fi

echo "=== Local smoke test (what nginx should hit) ==="
curl -sS -o /dev/null -w "localhost:8882/listings/ → %{http_code}\n" http://127.0.0.1:8882/listings/ || true
curl -sS http://127.0.0.1:8882/listings/ | grep -oE '[0-9]+ properties mile|Koi property' | head -3 || true

echo "=== Done. Hard-refresh https://propsurat.com/listings/ ==="
echo "If still empty, check: ss -tlnp | grep -E '8882|8000' && docker ps"
