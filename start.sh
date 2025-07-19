#!/bin/bash

echo "🚀 Starting Laravel application..."

# Set default environment variables if not set
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}
export APP_KEY=${APP_KEY:-base64:$(openssl rand -base64 32)}
export DB_CONNECTION=${DB_CONNECTION:-mysql}
export SESSION_DRIVER=${SESSION_DRIVER:-file}
export CACHE_STORE=${CACHE_STORE:-file}

# Set default port
export PORT=${PORT:-8000}

# Clear caches to ensure clean start
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Generate app key if not set
if [ "$APP_KEY" = "base64:$(openssl rand -base64 32)" ]; then
    echo "🔑 Generating new APP_KEY..."
    php artisan key:generate --force
fi

# Run migrations if database is available
echo "🗄️ Checking database connection..."
if php artisan migrate:status > /dev/null 2>&1; then
    echo "✅ Database connected, running migrations..."
    php artisan migrate --force
else
    echo "⚠️ Database not available, skipping migrations..."
fi

# Start the application
echo "🌐 Starting web server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT 