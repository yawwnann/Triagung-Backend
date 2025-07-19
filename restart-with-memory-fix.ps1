# Restart Docker containers with memory fix
Write-Host "🔄 Restarting Docker containers with memory fix..." -ForegroundColor Yellow

# Stop containers
Write-Host "🛑 Stopping containers..." -ForegroundColor White
docker-compose down

# Remove old images to force rebuild with new memory settings
Write-Host "🗑️ Removing old images..." -ForegroundColor White
docker-compose down --rmi all

# Rebuild and start containers
Write-Host "🚀 Rebuilding and starting containers..." -ForegroundColor White
docker-compose up -d --build

# Wait for containers to be ready
Write-Host "⏳ Waiting for containers to be ready..." -ForegroundColor White
Start-Sleep -Seconds 15

# Check container status
Write-Host "📊 Container status:" -ForegroundColor White
docker-compose ps

# Check memory usage
Write-Host "💾 Memory usage:" -ForegroundColor White
docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.MemPerc}}"

Write-Host ""
Write-Host "✅ Restart completed!" -ForegroundColor Green
Write-Host "🌐 Application is ready at: http://localhost:8000" -ForegroundColor Cyan
Write-Host "📝 Check logs with: docker-compose logs -f app" -ForegroundColor Cyan 