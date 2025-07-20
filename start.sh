#!/bin/bash

# Clear caches
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
php artisan migrate --force

# Start the server on port 8000
php artisan serve --host=0.0.0.0 --port=8000 