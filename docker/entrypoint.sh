#!/usr/bin/env sh

set -eu

cd /var/www

echo "[entrypoint] starting…"

# Ensure writable dirs exist (in case of mounted volumes)
mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true

# Basic sanity checks
if [ -z "${APP_KEY:-}" ]; then
  echo "[entrypoint] ERROR: APP_KEY is not set. Laravel will throw MissingAppKeyException."
  echo "[entrypoint] Hint: set APP_KEY in env or run: php artisan key:generate --force"
  exit 1
fi

echo "[entrypoint] clearing laravel caches (safe on every boot)…"
php artisan optimize:clear || true
php artisan view:clear || true

# If storage/framework/views is mounted and contains stale compiled views — wipe them.
rm -f storage/framework/views/*.php 2>/dev/null || true

# Optionally warm caches for production (won't fail boot if something is off)
if [ "${APP_ENV:-}" = "production" ]; then
  echo "[entrypoint] warming caches (production)…"
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
fi

echo "[entrypoint] launching supervisord…"
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf


