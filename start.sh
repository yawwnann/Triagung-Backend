#!/bin/bash

# Set default port if not provided
PORT=${PORT:-8000}

# Clear caches
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
php artisan migrate --force

# Start the server
php artisan serve --host=0.0.0.0 --port=$PORT 