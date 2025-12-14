#!/bin/bash

# Azure App Service startup script for Laravel with MongoDB

echo "Starting CineWave application..."

# Install MongoDB extension if not already installed
if ! php -m | grep -q mongodb; then
    echo "Installing MongoDB extension..."
    pecl install mongodb
    echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini
fi

# Copy environment file
if [ -f "/home/site/wwwroot/.env.azure" ]; then
    echo "Copying .env.azure to .env..."
    cp /home/site/wwwroot/.env.azure /home/site/wwwroot/.env
fi

# Set proper permissions
echo "Setting permissions..."
chmod -R 755 /home/site/wwwroot/storage
chmod -R 755 /home/site/wwwroot/bootstrap/cache

# Install dependencies if needed
if [ ! -d "/home/site/wwwroot/vendor" ]; then
    echo "Installing Composer dependencies..."
    cd /home/site/wwwroot
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Clear and cache configuration for better performance
echo "Clearing old cache..."
php /home/site/wwwroot/artisan config:clear || true
php /home/site/wwwroot/artisan route:clear || true
php /home/site/wwwroot/artisan view:clear || true

echo "Caching configuration..."
php /home/site/wwwroot/artisan config:cache
php /home/site/wwwroot/artisan route:cache
php /home/site/wwwroot/artisan view:cache

echo "Application started successfully!"
echo "PHP Version: $(php -v | head -n 1)"
echo "MongoDB extension: $(php -m | grep mongodb || echo 'Not installed')"
