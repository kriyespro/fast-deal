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

echo "=== Done. Site live at https://propsurat.com/ ==="
