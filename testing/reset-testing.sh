#!/bin/bash

echo "🔄 Resetting Docker testing environment..."

# Stop and remove containers
echo "🛑 Stopping and removing containers..."
docker-compose down -v

# Remove images (optional - uncomment if you want to rebuild from scratch)
# echo "🗑️  Removing images..."
# docker-compose down --rmi all

# Start fresh
echo "🚀 Starting fresh containers..."
docker-compose up -d --build

# Wait for database
echo "⏳ Waiting for database to be ready..."
sleep 15

# Setup database
echo "🗄️  Setting up database..."
docker-compose exec app php artisan migrate:fresh --seed

# Set permissions
echo "🔐 Setting permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

echo ""
echo "✅ Reset completed successfully!"
echo ""
echo "🌐 Application is ready at:"
echo "   Laravel App: http://localhost:8000"
echo "   phpMyAdmin:  http://localhost:8080"
echo ""
echo "🔗 Webhook URL: http://localhost:8000/api/midtrans/notification" 