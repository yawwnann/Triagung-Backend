# 🚀 Cara Menjalankan Laravel Docker Backend

## 📋 Prerequisites

-   Docker Desktop installed
-   Docker Compose installed
-   Git (untuk clone repository)

## 🏃‍♂️ Quick Start

### 1. **Start Application (Cara Paling Mudah)**

```powershell
# Jalankan script start
powershell -ExecutionPolicy Bypass -File start-app.ps1
```

### 2. **Manual Start**

```bash
# Start containers
docker-compose up -d

# Check status
docker-compose ps

# Test API
curl http://localhost:8000/api/produks
```

## 🌐 Access URLs

| Service         | URL                       | Description         |
| --------------- | ------------------------- | ------------------- |
| **Laravel App** | http://localhost:8000     | Main application    |
| **API Base**    | http://localhost:8000/api | API endpoints       |
| **phpMyAdmin**  | http://localhost:8080     | Database management |
| **Database**    | localhost:3306            | MySQL database      |

## 🗄️ Database Configuration

| Parameter | Value        |
| --------- | ------------ |
| Host      | localhost    |
| Port      | 3306         |
| Database  | laravel_test |
| Username  | root         |
| Password  | password     |

## 📁 Available Scripts

### **Application Management**

```powershell
# Start application
powershell -ExecutionPolicy Bypass -File start-app.ps1

# Stop application
powershell -ExecutionPolicy Bypass -File stop-app.ps1
```

### **Testing & Performance**

```powershell
# Test speed dengan menu pilihan
powershell -ExecutionPolicy Bypass -File test-speed.ps1

# Benchmark lengkap
powershell -ExecutionPolicy Bypass -File benchmark.ps1
```

### **Testing Scripts (dari folder testing/)**

```powershell
# Masuk ke folder testing
cd testing

# Test speed sederhana
powershell -ExecutionPolicy Bypass -File test-speed-simple.ps1

# Test speed yang diperbaiki
powershell -ExecutionPolicy Bypass -File test-speed-fixed.ps1

# Benchmark lengkap
powershell -ExecutionPolicy Bypass -File performance-benchmark.ps1

# Bash script (Linux/WSL)
bash test-speed.sh
```

## 🔧 Useful Commands

### **Container Management**

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# Restart containers
docker-compose restart

# View logs
docker-compose logs -f

# View specific service logs
docker-compose logs -f app
docker-compose logs -f db
```

### **Laravel Commands**

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Run seeders
docker-compose exec app php artisan db:seed

# Clear cache
docker-compose exec app php artisan cache:clear

# View routes
docker-compose exec app php artisan route:list

# Tinker
docker-compose exec app php artisan tinker
```

### **Database Commands**

```bash
# Access MySQL
docker-compose exec db mysql -u root -p laravel_test

# Backup database
docker-compose exec db mysqldump -u root -p laravel_test > backup.sql

# Restore database
docker-compose exec -T db mysql -u root -p laravel_test < backup.sql
```

## 📊 API Endpoints

### **Public Endpoints**

-   `GET /api/produks` - Get all products
-   `GET /api/produks/{id}` - Get specific product
-   `GET /api/kategoris` - Get all categories
-   `GET /api/banners` - Get banners
-   `GET /api/provinces` - Get provinces
-   `GET /api/regencies` - Get regencies
-   `GET /api/districts` - Get districts

### **Authentication Endpoints**

-   `POST /api/register` - User registration
-   `POST /api/login` - User login

### **Protected Endpoints (Require Auth)**

-   `GET /api/me` - Get user profile
-   `GET /api/my-orders` - Get user orders
-   `GET /api/cart` - Get cart
-   `POST /api/cart` - Add to cart
-   `POST /api/checkout` - Checkout
-   `GET /api/addresses` - Get addresses
-   `POST /api/addresses` - Add address

## 🧪 Testing & Performance

### **Test Speed dengan Menu**

```powershell
powershell -ExecutionPolicy Bypass -File test-speed.ps1
```

Menu yang tersedia:

1. Quick Speed Test (Basic)
2. Fixed Speed Test (Recommended)
3. Full Performance Benchmark
4. Simple Speed Test
5. Bash Speed Test (Linux/WSL)

### **Benchmark Lengkap**

```powershell
powershell -ExecutionPolicy Bypass -File benchmark.ps1
```

### **Testing Documentation**

Lihat `testing/README.md` untuk dokumentasi lengkap tentang script testing.

## 🛠️ Troubleshooting

### **Port Already in Use**

```bash
# Check what's using the port
netstat -ano | findstr :8000

# Kill the process
taskkill /PID <PID> /F
```

### **Container Won't Start**

```bash
# Check Docker status
docker info

# Check available ports
netstat -tulpn | grep :8000
```

### **Database Connection Issues**

```bash
# Check database container
docker-compose logs db

# Test connection
docker-compose exec app php artisan tinker
```

### **Permission Issues**

```bash
# Fix storage permissions
docker-compose exec app chmod -R 755 storage
docker-compose exec app chmod -R 755 bootstrap/cache
```

## 📈 Performance Monitoring

### **View Resource Usage**

```bash
# Container stats
docker stats

# System info
docker system df
```

### **Test Performance**

```powershell
# Test speed dengan menu
powershell -ExecutionPolicy Bypass -File test-speed.ps1

# Benchmark lengkap
powershell -ExecutionPolicy Bypass -File benchmark.ps1
```

## 🔄 Development Workflow

### **1. Start Development**

```powershell
powershell -ExecutionPolicy Bypass -File start-app.ps1
```

### **2. Make Changes**

-   Edit files in your IDE
-   Changes are automatically reflected (volume mounted)

### **3. Test Changes**

```bash
# Test API
curl http://localhost:8000/api/produks

# Run tests
docker-compose exec app php artisan test

# Test performance
powershell -ExecutionPolicy Bypass -File test-speed.ps1
```

### **4. Stop Development**

```powershell
powershell -ExecutionPolicy Bypass -File stop-app.ps1
```

## 🎯 Tips & Best Practices

1. **Always use scripts** for start/stop operations
2. **Monitor logs** when debugging issues
3. **Test API endpoints** after making changes
4. **Use phpMyAdmin** for database management
5. **Run performance tests** regularly
6. **Backup database** before major changes
7. **Use testing folder** for organized test scripts

## 📞 Support

Jika ada masalah, cek:

1. Docker Desktop running
2. Ports 8000, 3306, 8080 available
3. Container logs: `docker-compose logs -f`
4. API response: `curl http://localhost:8000/api/produks`
5. Testing documentation: `testing/README.md`

---

**Happy Coding! 🚀**
