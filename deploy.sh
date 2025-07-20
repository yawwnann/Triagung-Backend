#!/bin/bash

echo "🚀 Starting Railway Deployment..."

# Set default port
if [ -z "$PORT" ]; then
    PORT=8000
fi

echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

echo "🗄️ Running database migrations..."
php artisan migrate --force

echo "🧹 Clearing caches..."
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan route:clear

echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🔑 Generating application key if not exists..."
if [ -z "$APP_KEY" ]; then
    php artisan key:generate
fi

echo "🌐 Starting server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT 