#!/bin/bash

echo "🔧 Setting up storage permissions..."

# Create necessary directories
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Create .gitkeep files if needed
touch storage/framework/cache/.gitkeep
touch storage/framework/sessions/.gitkeep
touch storage/framework/views/.gitkeep
touch storage/logs/.gitkeep

echo "✅ Storage setup completed!" 