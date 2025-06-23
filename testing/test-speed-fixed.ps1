# Docker Speed Test untuk Laravel Backend (PowerShell) - Fixed
Write-Host "Docker Speed Test untuk Laravel Backend" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Green

# Test 1: Response Time API (using existing endpoints)
Write-Host ""
Write-Host "Test 1: Response Time API" -ForegroundColor Yellow
Write-Host "------------------------" -ForegroundColor Yellow

$totalTime = 0
$requests = 10

for ($i = 1; $i -le $requests; $i++) {
    $startTime = Get-Date
    try {
        $response = Invoke-WebRequest -Uri "http://localhost:8000/api/produks" -UseBasicParsing -TimeoutSec 10
        $endTime = Get-Date
        $duration = ($endTime - $startTime).TotalMilliseconds
        $totalTime += $duration
        Write-Host "Request $i`: $([math]::Round($duration, 2))ms (Status: $($response.StatusCode))" -ForegroundColor White
    }
    catch {
        Write-Host "Request $i`: Failed - $($_.Exception.Message)" -ForegroundColor Red
    }
}

$averageTime = $totalTime / $requests
Write-Host "Average Response Time: $([math]::Round($averageTime, 2))ms" -ForegroundColor Cyan

# Test 2: Container Resource Usage
Write-Host ""
Write-Host "Test 2: Container Resource Usage" -ForegroundColor Yellow
Write-Host "-------------------------------" -ForegroundColor Yellow

Write-Host "Memory Usage:" -ForegroundColor White
docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.MemPerc}}"

Write-Host ""
Write-Host "Container Status:" -ForegroundColor White
docker-compose ps

# Test 3: Load Test (Simple)
Write-Host ""
Write-Host "Test 3: Simple Load Test" -ForegroundColor Yellow
Write-Host "-----------------------" -ForegroundColor Yellow

Write-Host "Testing 20 concurrent requests..." -ForegroundColor White
$startTime = Get-Date

# Run 20 requests in parallel
$jobs = @()
for ($i = 1; $i -le 20; $i++) {
    $jobs += Start-Job -ScriptBlock {
        param($url)
        try {
            $response = Invoke-WebRequest -Uri $url -UseBasicParsing -TimeoutSec 5
            return $response.StatusCode
        }
        catch {
            return "Failed"
        }
    } -ArgumentList "http://localhost:8000/api/produks"
}

# Wait for all jobs to complete
$results = $jobs | Wait-Job | Receive-Job
$jobs | Remove-Job

$endTime = Get-Date
$loadTime = ($endTime - $startTime).TotalMilliseconds
$successCount = ($results | Where-Object { $_ -eq 200 }).Count

Write-Host "Load Test Time (20 requests): $([math]::Round($loadTime, 2))ms" -ForegroundColor White
Write-Host "Successful Requests: $successCount/20" -ForegroundColor White

# Test 4: Database Connection Speed
Write-Host ""
Write-Host "Test 4: Database Connection Speed" -ForegroundColor Yellow
Write-Host "--------------------------------" -ForegroundColor Yellow

$startTime = Get-Date
try {
    docker-compose exec -T app php artisan tinker --execute="echo 'DB connected';" | Out-Null
    $endTime = Get-Date
    $dbTime = ($endTime - $startTime).TotalMilliseconds
    Write-Host "Database Connection Time: $([math]::Round($dbTime, 2))ms" -ForegroundColor White
}
catch {
    Write-Host "Database Connection: Failed" -ForegroundColor Red
}

# Test 5: Docker Performance
Write-Host ""
Write-Host "Test 5: Docker Performance" -ForegroundColor Yellow
Write-Host "-------------------------" -ForegroundColor Yellow

Write-Host "Docker Info:" -ForegroundColor White
docker version --format "Client: {{.Client.Version}}"
docker version --format "Server: {{.Server.Version}}"

Write-Host ""
Write-Host "Docker System Info:" -ForegroundColor White
docker system df

# Test 6: Laravel Route List
Write-Host ""
Write-Host "Test 6: Laravel Route List" -ForegroundColor Yellow
Write-Host "------------------------" -ForegroundColor Yellow

Write-Host "Available API Routes:" -ForegroundColor White
docker-compose exec app php artisan route:list --path=api

Write-Host ""
Write-Host "Speed Test Selesai!" -ForegroundColor Green
Write-Host "===================" -ForegroundColor Green
Write-Host "Summary:" -ForegroundColor Cyan
Write-Host "- Average API Response: $([math]::Round($averageTime, 2))ms" -ForegroundColor White
Write-Host "- Database Connection: $([math]::Round($dbTime, 2))ms" -ForegroundColor White
Write-Host "- Load Test (20 req): $([math]::Round($loadTime, 2))ms" -ForegroundColor White
Write-Host "- Successful Requests: $successCount/20" -ForegroundColor White

Write-Host ""
Write-Host "Tips untuk Optimasi:" -ForegroundColor Yellow
Write-Host "- Gunakan Redis untuk caching" -ForegroundColor White
Write-Host "- Optimize database queries" -ForegroundColor White
Write-Host "- Enable OPcache di PHP" -ForegroundColor White
Write-Host "- Gunakan CDN untuk static files" -ForegroundColor White
Write-Host "- Monitor dengan Docker stats" -ForegroundColor White 