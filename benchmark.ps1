# Performance Benchmark Script - Main Entry Point
Write-Host "Docker Laravel Performance Benchmark" -ForegroundColor Green
Write-Host "===================================" -ForegroundColor Green

# Check if testing folder exists
if (-not (Test-Path "testing")) {
    Write-Host "❌ Testing folder not found!" -ForegroundColor Red
    Write-Host "Please run this script from the project root directory." -ForegroundColor Yellow
    exit 1
}

Write-Host ""
Write-Host "Starting Full Performance Benchmark..." -ForegroundColor Yellow
Write-Host "This will test:" -ForegroundColor White
Write-Host "- Individual API endpoints performance" -ForegroundColor White
Write-Host "- Database connection speed" -ForegroundColor White
Write-Host "- Container resource usage" -ForegroundColor White
Write-Host "- Load testing with different concurrency levels" -ForegroundColor White
Write-Host "- System performance metrics" -ForegroundColor White
Write-Host ""

# Ask for confirmation
$confirm = Read-Host "Continue? (y/N)"
if ($confirm -ne "y" -and $confirm -ne "Y") {
    Write-Host "Benchmark cancelled." -ForegroundColor Yellow
    exit 0
}

# Run the benchmark
Write-Host "Running benchmark..." -ForegroundColor Yellow
& ".\testing\performance-benchmark.ps1"

Write-Host ""
Write-Host "Benchmark completed!" -ForegroundColor Green 