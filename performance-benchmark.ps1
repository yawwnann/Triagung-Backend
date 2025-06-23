# Docker Laravel Performance Benchmark
Write-Host "Docker Laravel Performance Benchmark" -ForegroundColor Green
Write-Host "===================================" -ForegroundColor Green

# Function to measure response time
function Measure-ResponseTime {
    param($url, $description)
    $startTime = Get-Date
    try {
        $response = Invoke-WebRequest -Uri $url -UseBasicParsing -TimeoutSec 10
        $endTime = Get-Date
        $duration = ($endTime - $startTime).TotalMilliseconds
        Write-Host "$description`: $([math]::Round($duration, 2))ms (Status: $($response.StatusCode))" -ForegroundColor White
        return $duration
    }
    catch {
        Write-Host "$description`: Failed - $($_.Exception.Message)" -ForegroundColor Red
        return 0
    }
}

# Test 1: Individual API Endpoints Performance
Write-Host ""
Write-Host "Test 1: Individual API Endpoints Performance" -ForegroundColor Yellow
Write-Host "--------------------------------------------" -ForegroundColor Yellow

$endpoints = @(
    @{url="http://localhost:8000/api/produks"; desc="Products API"},
    @{url="http://localhost:8000/api/kategoris"; desc="Categories API"},
    @{url="http://localhost:8000/api/banners"; desc="Banners API"},
    @{url="http://localhost:8000/api/provinces"; desc="Provinces API"},
    @{url="http://localhost:8000/api/regencies"; desc="Regencies API"}
)

$endpointTimes = @()
foreach ($endpoint in $endpoints) {
    $time = Measure-ResponseTime -url $endpoint.url -description $endpoint.desc
    $endpointTimes += $time
}

# Test 2: Database Performance
Write-Host ""
Write-Host "Test 2: Database Performance" -ForegroundColor Yellow
Write-Host "---------------------------" -ForegroundColor Yellow

$startTime = Get-Date
try {
    docker-compose exec -T app php artisan tinker --execute="echo 'DB connected';" | Out-Null
    $endTime = Get-Date
    $dbTime = ($endTime - $startTime).TotalMilliseconds
    Write-Host "Database Connection: $([math]::Round($dbTime, 2))ms" -ForegroundColor White
}
catch {
    Write-Host "Database Connection: Failed" -ForegroundColor Red
    $dbTime = 0
}

# Test 3: Container Resource Usage
Write-Host ""
Write-Host "Test 3: Container Resource Usage" -ForegroundColor Yellow
Write-Host "-------------------------------" -ForegroundColor Yellow

Write-Host "Memory Usage:" -ForegroundColor White
docker stats --no-stream --format "table {{.Container}}\t{{.CPUPerc}}\t{{.MemUsage}}\t{{.MemPerc}}"

# Test 4: Load Testing with Different Concurrency Levels
Write-Host ""
Write-Host "Test 4: Load Testing" -ForegroundColor Yellow
Write-Host "-------------------" -ForegroundColor Yellow

$concurrencyLevels = @(5, 10, 20, 50)
$loadTestResults = @()

foreach ($concurrency in $concurrencyLevels) {
    Write-Host "Testing $concurrency concurrent requests..." -ForegroundColor White
    $startTime = Get-Date
    
    $jobs = @()
    for ($i = 1; $i -le $concurrency; $i++) {
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
    
    $results = $jobs | Wait-Job | Receive-Job
    $jobs | Remove-Job
    
    $endTime = Get-Date
    $loadTime = ($endTime - $startTime).TotalMilliseconds
    $successCount = ($results | Where-Object { $_ -eq 200 }).Count
    $successRate = ($successCount / $concurrency) * 100
    
    Write-Host "  $concurrency concurrent: $([math]::Round($loadTime, 2))ms, $successCount/$concurrency successful ($([math]::Round($successRate, 1))%)" -ForegroundColor White
    
    $loadTestResults += @{
        Concurrency = $concurrency
        Time = $loadTime
        SuccessCount = $successCount
        SuccessRate = $successRate
    }
}

# Test 5: System Performance
Write-Host ""
Write-Host "Test 5: System Performance" -ForegroundColor Yellow
Write-Host "-------------------------" -ForegroundColor Yellow

Write-Host "Docker Version:" -ForegroundColor White
docker version --format "Client: {{.Client.Version}}"
docker version --format "Server: {{.Server.Version}}"

Write-Host ""
Write-Host "Docker System Info:" -ForegroundColor White
docker system df

Write-Host ""
Write-Host "Container Details:" -ForegroundColor White
docker-compose ps

# Test 6: Laravel Application Info
Write-Host ""
Write-Host "Test 6: Laravel Application Info" -ForegroundColor Yellow
Write-Host "-------------------------------" -ForegroundColor Yellow

Write-Host "Laravel Version:" -ForegroundColor White
docker-compose exec app php artisan --version

Write-Host ""
Write-Host "PHP Version:" -ForegroundColor White
docker-compose exec app php --version

Write-Host ""
Write-Host "Available Routes:" -ForegroundColor White
docker-compose exec app php artisan route:list --path=api | Select-Object -First 10

# Performance Summary
Write-Host ""
Write-Host "Performance Benchmark Summary" -ForegroundColor Green
Write-Host "=============================" -ForegroundColor Green

$avgEndpointTime = ($endpointTimes | Where-Object { $_ -gt 0 } | Measure-Object -Average).Average
Write-Host "Average API Response Time: $([math]::Round($avgEndpointTime, 2))ms" -ForegroundColor Cyan
Write-Host "Database Connection Time: $([math]::Round($dbTime, 2))ms" -ForegroundColor Cyan

Write-Host ""
Write-Host "Load Test Results:" -ForegroundColor Cyan
foreach ($result in $loadTestResults) {
    Write-Host "  $($result.Concurrency) concurrent: $([math]::Round($result.Time, 2))ms ($([math]::Round($result.SuccessRate, 1))% success)" -ForegroundColor White
}

# Performance Rating
Write-Host ""
Write-Host "Performance Rating:" -ForegroundColor Yellow
if ($avgEndpointTime -lt 100) {
    Write-Host "  API Response: EXCELLENT (< 100ms)" -ForegroundColor Green
} elseif ($avgEndpointTime -lt 300) {
    Write-Host "  API Response: GOOD (100-300ms)" -ForegroundColor Yellow
} else {
    Write-Host "  API Response: NEEDS IMPROVEMENT (> 300ms)" -ForegroundColor Red
}

if ($dbTime -lt 500) {
    Write-Host "  Database: EXCELLENT (< 500ms)" -ForegroundColor Green
} elseif ($dbTime -lt 1000) {
    Write-Host "  Database: GOOD (500-1000ms)" -ForegroundColor Yellow
} else {
    Write-Host "  Database: NEEDS IMPROVEMENT (> 1000ms)" -ForegroundColor Red
}

# Optimization Recommendations
Write-Host ""
Write-Host "Optimization Recommendations:" -ForegroundColor Yellow
Write-Host "- Enable OPcache in PHP for better performance" -ForegroundColor White
Write-Host "- Add Redis caching for frequently accessed data" -ForegroundColor White
Write-Host "- Optimize database queries and add indexes" -ForegroundColor White
Write-Host "- Use CDN for static assets" -ForegroundColor White
Write-Host "- Consider using PHP-FPM instead of Apache mod_php" -ForegroundColor White
Write-Host "- Monitor memory usage and adjust container limits" -ForegroundColor White
Write-Host "- Use database connection pooling" -ForegroundColor White 