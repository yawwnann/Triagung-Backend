#!/bin/bash

echo "🚀 Setting up Docker testing environment for Midtrans webhook..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Copy environment file
if [ ! -f .env ]; then
    echo "📝 Creating .env file from template..."
    cp env.docker .env
fi

# Build and start containers
echo "🐳 Building and starting Docker containers..."
docker-compose up -d --build

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 15

# Generate application key
echo "🔑 Generating application key..."
docker-compose exec app php artisan key:generate

# Run migrations
echo "🗄️  Running database migrations..."
docker-compose exec app php artisan migrate:fresh

# Seed database
echo "🌱 Seeding database with test data..."
docker-compose exec app php artisan db:seed

# Set proper permissions
echo "🔐 Setting proper permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

echo ""
echo "✅ Setup completed successfully!"
echo ""
echo "🌐 Application URLs:"
echo "   Laravel App: http://localhost:8000"
echo "   phpMyAdmin:  http://localhost:8080"
echo "   API Base:    http://localhost:8000/api"
echo ""
echo "🔗 Midtrans Webhook URL:"
echo "   http://localhost:8000/api/midtrans/notification"
echo ""
echo "📊 Database Access:"
echo "   Host: localhost"
echo "   Port: 3306"
echo "   Database: laravel_test"
echo "   Username: root"
echo "   Password: password"
echo ""
echo "🛠️  Useful Commands:"
echo "   View logs: docker-compose logs -f app"
echo "   Restart:   docker-compose restart"
echo "   Stop:      docker-compose down"
echo "   Reset:     ./reset-testing.sh"
echo ""
echo "🎯 Next Steps:"
echo "   1. Install ngrok: https://ngrok.com/download"
echo "   2. Run: ngrok http 8000"
echo "   3. Copy the ngrok URL and set it in Midtrans Dashboard"
echo "   4. Test webhook with a transaction" 