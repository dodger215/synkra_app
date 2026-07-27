#!/bin/sh
set -e

# Ensure Laravel storage and bootstrap directories exist and are writable
mkdir -p /var/www/storage/framework/cache/data
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/logs
mkdir -p /var/www/bootstrap/cache

# Ensure SQLite database exists
if [ ! -f /var/www/database/database.sqlite ]; then
    echo "Creating SQLite database..."
    touch /var/www/database/database.sqlite
fi

# Set permissions for storage and cache
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database
chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Link storage
php artisan storage:link

# Run Laravel on 0.0.0.0
echo "Starting Laravel server on 0.0.0.0:8000..."
exec php artisan serve --host=0.0.0.0 --port=8000
