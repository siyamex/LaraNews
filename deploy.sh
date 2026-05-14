#!/usr/bin/env bash
# LaraNews — Production Deploy Script
# Usage: ./deploy.sh [--skip-build] [--migrate-fresh]
set -e

APP_DIR="$(cd "$(dirname "$0")" && pwd)"
cd "$APP_DIR"

echo "==> Pulling latest code..."
git pull origin main

echo "==> Installing PHP dependencies (production)..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Build frontend assets unless skipped
if [[ "$*" != *--skip-build* ]]; then
    echo "==> Installing frontend dependencies..."
    npm ci
    echo "==> Building frontend assets..."
    npm run build
    echo "==> Cleaning up node_modules..."
    rm -rf node_modules
fi

echo "==> Putting app into maintenance mode..."
php artisan down --render="errors::503" --retry=60

echo "==> Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

if [[ "$*" == *--migrate-fresh* ]]; then
    echo "==> Running fresh migrations (DESTRUCTIVE)..."
    php artisan migrate:fresh --seed --force
else
    echo "==> Running migrations..."
    php artisan migrate --force
fi

echo "==> Rebuilding optimised caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "==> Linking storage..."
php artisan storage:link --force

echo "==> Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "==> Restarting queues..."
php artisan queue:restart

echo "==> Bringing app back online..."
php artisan up

echo ""
echo "✓ Deploy complete."
