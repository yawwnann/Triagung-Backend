# Docker Setup untuk Testing Midtrans Webhook

## 🚀 Quick Start

### 1. Setup Environment

```bash
# Clone repository (jika belum)
git clone <repository-url>
cd BACKEND

# Setup testing environment
chmod +x setup-testing.sh
./setup-testing.sh
```

### 2. Test Webhook

```bash
# Test webhook endpoint
chmod +x test-webhook.sh
./test-webhook.sh
```

### 3. Reset Environment (jika perlu)

```bash
chmod +x reset-testing.sh
./reset-testing.sh
```

## 📋 Prerequisites

-   Docker Desktop installed
-   Docker Compose installed
-   Git (untuk clone repository)

## 🏗️ Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Laravel App   │    │   MySQL 8.0     │    │   phpMyAdmin    │
│   (Port 8000)   │◄──►│   (Port 3306)   │◄──►│   (Port 8080)   │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 🌐 Access URLs

| Service     | URL                                             | Description         |
| ----------- | ----------------------------------------------- | ------------------- |
| Laravel App | http://localhost:8000                           | Main application    |
| API Base    | http://localhost:8000/api                       | API endpoints       |
| Webhook     | http://localhost:8000/api/midtrans/notification | Midtrans webhook    |
| phpMyAdmin  | http://localhost:8080                           | Database management |

## 🗄️ Database Configuration

| Parameter | Value        |
| --------- | ------------ |
| Host      | localhost    |
| Port      | 3306         |
| Database  | laravel_test |
| Username  | root         |
| Password  | password     |

## 🔧 Configuration Files

### docker-compose.yml

-   Laravel application container
-   MySQL database container
-   phpMyAdmin container
-   Network configuration
-   Volume persistence

### Dockerfile

-   PHP 8.2 with Apache
-   Required PHP extensions
-   Composer installation
-   Apache configuration

### env.docker

-   Environment variables for Docker
-   Database configuration
-   Midtrans sandbox settings
-   JWT configuration

## 🧪 Testing Workflow

### 1. Local Testing

```bash
# Start environment
./setup-testing.sh

# Test webhook
./test-webhook.sh

# Check database
# Open http://localhost:8080
```

### 2. Public Testing dengan Ngrok

```bash
# Install ngrok
# Download from https://ngrok.com/download

# Expose local port
ngrok http 8000

# Copy ngrok URL (e.g., https://abc123.ngrok.io)
# Set di Midtrans Dashboard:
# https://abc123.ngrok.io/api/midtrans/notification
```

### 3. Test Transaksi Lengkap

1. Start environment: `./setup-testing.sh`
2. Start ngrok: `ngrok http 8000`
3. Set webhook URL di Midtrans Dashboard
4. Buat transaksi di frontend
5. Monitor webhook callback
6. Check database untuk status update

## 📊 Monitoring

### View Logs

```bash
# Laravel logs
docker-compose logs -f app

# Database logs
docker-compose logs -f db

# All logs
docker-compose logs -f
```

### Check Container Status

```bash
# View running containers
docker-compose ps

# View container details
docker-compose exec app php artisan route:list
```

## 🔄 Reset dan Maintenance

### Reset Environment

```bash
# Reset everything (database, containers)
./reset-testing.sh
```

### Update Code

```bash
# Rebuild containers after code changes
docker-compose up -d --build
```

### Backup Database

```bash
# Backup
docker-compose exec db mysqldump -u root -p laravel_test > backup.sql

# Restore
docker-compose exec -T db mysql -u root -p laravel_test < backup.sql
```

## 🛠️ Troubleshooting

### Container Won't Start

```bash
# Check Docker status
docker info

# Check available ports
netstat -tulpn | grep :8000
netstat -tulpn | grep :3306
netstat -tulpn | grep :8080
```

### Database Connection Issues

```bash
# Check database container
docker-compose logs db

# Test connection
docker-compose exec app php artisan tinker
```

### Permission Issues

```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chmod -R 755 /var/www/html/storage
```

### Webhook Not Working

```bash
# Test endpoint manually
curl -X POST http://localhost:8000/api/midtrans/notification \
  -H "Content-Type: application/json" \
  -d '{"test": "data"}'

# Check Laravel logs
docker-compose logs -f app
```

## 📝 Environment Variables

### Required for Testing

```env
APP_ENV=local
APP_DEBUG=true
DB_HOST=db
DB_DATABASE=laravel_test
DB_USERNAME=root
DB_PASSWORD=password
MIDTRANS_IS_PRODUCTION=false
```

### Midtrans Configuration

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxx
MIDTRANS_IS_PRODUCTION=false
```

## 🎯 Best Practices

1. **Always use HTTPS** for production webhook URLs
2. **Test thoroughly** before going live
3. **Monitor logs** during testing
4. **Backup database** regularly
5. **Use ngrok** for public testing
6. **Keep containers updated**

## 📞 Support

Jika ada masalah:

1. Check logs: `docker-compose logs -f`
2. Reset environment: `./reset-testing.sh`
3. Check Docker status: `docker info`
4. Verify ports are available

## 🔗 Useful Commands

```bash
# Start environment
./setup-testing.sh

# Stop environment
docker-compose down

# Restart environment
docker-compose restart

# View logs
docker-compose logs -f app

# Access container shell
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

# Test webhook
./test-webhook.sh

# Reset everything
./reset-testing.sh
```
