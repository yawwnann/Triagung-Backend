# Script untuk Menghentikan Laravel Docker Backend
Write-Host "Stopping Laravel Docker Backend..." -ForegroundColor Red
Write-Host "=================================" -ForegroundColor Red

# Check if containers are running
Write-Host "Checking container status..." -ForegroundColor Yellow
$containers = docker-compose ps --format "table {{.Name}}\t{{.Status}}"

if ($containers -like "*Up*") {
    Write-Host "Stopping containers..." -ForegroundColor Yellow
    docker-compose down
    
    Write-Host ""
    Write-Host "✅ Application stopped successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "To start again, run:" -ForegroundColor Cyan
    Write-Host "powershell -ExecutionPolicy Bypass -File start-app.ps1" -ForegroundColor White
    Write-Host "or" -ForegroundColor White
    Write-Host "docker-compose up -d" -ForegroundColor White
} else {
    Write-Host "❌ No containers are currently running." -ForegroundColor Red
    Write-Host ""
    Write-Host "To start the application, run:" -ForegroundColor Cyan
    Write-Host "powershell -ExecutionPolicy Bypass -File start-app.ps1" -ForegroundColor White
}

Write-Host ""
Write-Host "Container cleanup completed!" -ForegroundColor Green 