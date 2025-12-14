#!/bin/bash

# Azure App Service startup script for Laravel

echo "Starting CineWave application..."

# Set proper permissions
chmod -R 755 /home/site/wwwroot/storage
chmod -R 755 /home/site/wwwroot/bootstrap/cache

# Install dependencies if needed
if [ ! -d "/home/site/wwwroot/vendor" ]; then
    echo "Installing Composer dependencies..."
    cd /home/site/wwwroot
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Cache configuration for better performance
echo "Caching configuration..."
php /home/site/wwwroot/artisan config:cache
php /home/site/wwwroot/artisan route:cache
php /home/site/wwwroot/artisan view:cache

echo "Application started successfully!"
