# Script untuk Menjalankan Laravel Docker Backend
Write-Host "Starting Laravel Docker Backend..." -ForegroundColor Green
Write-Host "=================================" -ForegroundColor Green

# Function to check if port is in use
function Test-Port {
    param($port)
    try {
        $connection = New-Object System.Net.Sockets.TcpClient
        $connection.Connect("localhost", $port)
        $connection.Close()
        return $true
    }
    catch {
        return $false
    }
}

# Check if containers are already running
Write-Host "Checking container status..." -ForegroundColor Yellow
$containers = docker-compose ps --format "table {{.Name}}\t{{.Status}}"

if ($containers -like "*Up*") {
    Write-Host "Containers are already running!" -ForegroundColor Green
} else {
    Write-Host "Starting containers..." -ForegroundColor Yellow
    docker-compose up -d
    
    # Wait for containers to be ready
    Write-Host "Waiting for containers to be ready..." -ForegroundColor Yellow
    Start-Sleep -Seconds 10
}

# Check container status
Write-Host ""
Write-Host "Container Status:" -ForegroundColor Yellow
docker-compose ps

# Wait for database to be ready
Write-Host ""
Write-Host "Waiting for database to be ready..." -ForegroundColor Yellow
$maxAttempts = 30
$attempt = 0

do {
    $attempt++
    try {
        docker-compose exec -T app php artisan tinker --execute="echo 'DB OK';" | Out-Null
        Write-Host "Database is ready!" -ForegroundColor Green
        break
    }
    catch {
        if ($attempt -ge $maxAttempts) {
            Write-Host "Database connection failed after $maxAttempts attempts" -ForegroundColor Red
            break
        }
        Write-Host "Waiting for database... (attempt $attempt/$maxAttempts)" -ForegroundColor Yellow
        Start-Sleep -Seconds 2
    }
} while ($true)

# Test API endpoints
Write-Host ""
Write-Host "Testing API endpoints..." -ForegroundColor Yellow

$endpoints = @(
    @{url="http://localhost:8000/api/produks"; name="Products API"},
    @{url="http://localhost:8000/api/kategoris"; name="Categories API"},
    @{url="http://localhost:8000/api/banners"; name="Banners API"}
)

foreach ($endpoint in $endpoints) {
    try {
        $response = Invoke-WebRequest -Uri $endpoint.url -UseBasicParsing -TimeoutSec 5
        if ($response.StatusCode -eq 200) {
            Write-Host "✅ $($endpoint.name): OK" -ForegroundColor Green
        } else {
            Write-Host "❌ $($endpoint.name): Status $($response.StatusCode)" -ForegroundColor Red
        }
    }
    catch {
        Write-Host "❌ $($endpoint.name): Failed" -ForegroundColor Red
    }
}

# Show access information
Write-Host ""
Write-Host "🚀 Application is running!" -ForegroundColor Green
Write-Host "=========================" -ForegroundColor Green
Write-Host ""
Write-Host "Access URLs:" -ForegroundColor Cyan
Write-Host "• Laravel App:     http://localhost:8000" -ForegroundColor White
Write-Host "• API Base:        http://localhost:8000/api" -ForegroundColor White
Write-Host "• phpMyAdmin:      http://localhost:8080" -ForegroundColor White
Write-Host "• Database:        localhost:3306" -ForegroundColor White
Write-Host ""
Write-Host "Database Credentials:" -ForegroundColor Cyan
Write-Host "• Database: laravel_test" -ForegroundColor White
Write-Host "• Username: root" -ForegroundColor White
Write-Host "• Password: password" -ForegroundColor White
Write-Host ""
Write-Host "Useful Commands:" -ForegroundColor Cyan
Write-Host "• View logs:       docker-compose logs -f" -ForegroundColor White
Write-Host "• Stop app:        docker-compose down" -ForegroundColor White
Write-Host "• Restart app:     docker-compose restart" -ForegroundColor White
Write-Host "• Test speed:      powershell -ExecutionPolicy Bypass -File test-speed-fixed.ps1" -ForegroundColor White
Write-Host "• Benchmark:       powershell -ExecutionPolicy Bypass -File performance-benchmark.ps1" -ForegroundColor White
Write-Host ""
Write-Host "Press Ctrl+C to stop the application" -ForegroundColor Yellow 