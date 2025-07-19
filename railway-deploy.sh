#!/bin/bash

echo "🚀 Deploying Laravel + Filament to Railway..."

# Clear all caches first
echo "📦 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Create storage symlink if needed
echo "🔗 Creating storage symlink..."
php artisan storage:link

# Clear Filament cache
echo "🧹 Clearing Filament cache..."
php artisan filament:cache-components

echo "✅ Deployment completed successfully!"
echo "🌐 Your app should be available at: https://your-railway-app.railway.app" 