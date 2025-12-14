#!/bin/sh

echo "Running post-deployment script..."

# Install dependencies
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar install --no-dev --optimize-autoloader

# Clear and cache config
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --force

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "Post-deployment completed!"
