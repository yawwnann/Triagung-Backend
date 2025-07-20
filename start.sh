#!/bin/bash

# Set default port if not provided
if [ -z "$PORT" ]; then
    PORT=8000
fi

# Clear caches
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
php artisan migrate --force

# Start the server with explicit port
php artisan serve --host=0.0.0.0 --port=$PORT 