#!/usr/bin/env bash
# One-click deployment: run from project root — bash deploy.sh
# Requires: git remote, PHP, Composer, writable web server user for writable/
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

echo "=== FastDeal deploy ($(date -u +%Y-%m-%dT%H:%M:%SZ)) ==="

if [[ -d .git ]]; then
  echo "Pulling latest..."
  git pull origin main
else
  echo "Note: no .git directory — skipping git pull."
fi

echo "Composer install (production)..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "Database migrations..."
php spark migrate --all --no-header

echo "Clear caches..."
php spark cache:clear 2>/dev/null || true

echo "Writable permissions..."
chmod -R 775 writable/ 2>/dev/null || true
[[ -d storage ]] && chmod -R 775 storage/ 2>/dev/null || true

echo "=== Deployment finished successfully ==="
