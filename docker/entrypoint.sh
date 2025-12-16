#!/usr/bin/env sh
set -e

cd /var/www/html

php artisan package:discover --ansi

# Create .env from example if it doesn't exist
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Generate app key if not set
if ! grep -q '^APP_KEY=' .env || [ -z "$(grep '^APP_KEY=' .env | cut -d '=' -f2)" ]; then
  php artisan key:generate --force
fi

# Ensure SQLite database file exists (if using sqlite)
if grep -q "DB_CONNECTION=sqlite" .env && [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
  php artisan migrate --seed
fi

# Run database migrations
php artisan migrate --force

# Start the Laravel development server
php artisan serve --host=0.0.0.0 --port=8000


