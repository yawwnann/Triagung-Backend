# Test Speed Script - Main Entry Point
Write-Host "Docker Speed Test untuk Laravel Backend" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Green

# Check if testing folder exists
if (-not (Test-Path "testing")) {
    Write-Host "❌ Testing folder not found!" -ForegroundColor Red
    Write-Host "Please run this script from the project root directory." -ForegroundColor Yellow
    exit 1
}

# Show available test options
Write-Host ""
Write-Host "Available Test Options:" -ForegroundColor Cyan
Write-Host "1. Quick Speed Test (Basic)" -ForegroundColor White
Write-Host "2. Fixed Speed Test (Recommended)" -ForegroundColor White
Write-Host "3. Full Performance Benchmark" -ForegroundColor White
Write-Host "4. Simple Speed Test" -ForegroundColor White
Write-Host "5. Bash Speed Test (Linux/WSL)" -ForegroundColor White
Write-Host ""

# Get user choice
$choice = Read-Host "Enter your choice (1-5) or press Enter for default (2)"

# Default to option 2 if no choice
if ([string]::IsNullOrWhiteSpace($choice)) {
    $choice = "2"
}

# Execute chosen test
switch ($choice) {
    "1" {
        Write-Host "Running Quick Speed Test..." -ForegroundColor Yellow
        & ".\testing\test-speed-simple.ps1"
    }
    "2" {
        Write-Host "Running Fixed Speed Test..." -ForegroundColor Yellow
        & ".\testing\test-speed-fixed.ps1"
    }
    "3" {
        Write-Host "Running Full Performance Benchmark..." -ForegroundColor Yellow
        & ".\testing\performance-benchmark.ps1"
    }
    "4" {
        Write-Host "Running Simple Speed Test..." -ForegroundColor Yellow
        & ".\testing\test-speed-simple.ps1"
    }
    "5" {
        Write-Host "Running Bash Speed Test..." -ForegroundColor Yellow
        if (Get-Command bash -ErrorAction SilentlyContinue) {
            bash ".\testing\test-speed.sh"
        } else {
            Write-Host "❌ Bash not available. Please use WSL or Linux." -ForegroundColor Red
        }
    }
    default {
        Write-Host "❌ Invalid choice. Running default test..." -ForegroundColor Red
        & ".\testing\test-speed-fixed.ps1"
    }
}

Write-Host ""
Write-Host "Test completed!" -ForegroundColor Green 