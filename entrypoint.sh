#!/bin/bash

# Create SQLite database if it doesn't exist
mkdir -p database
touch database/database.sqlite
chmod -R 775 database
chown -R www-data:www-data database

# Run migrations
php artisan migrate --force

# Clear and cache config/routes/views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
exec apache2-foreground
