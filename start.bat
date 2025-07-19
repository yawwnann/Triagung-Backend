@echo off

REM Set default port if not provided
if "%PORT%"=="" set PORT=8000

REM Clear caches
php artisan config:clear
php artisan view:clear
php artisan cache:clear

REM Run migrations
php artisan migrate --force

REM Start the server
php artisan serve --host=0.0.0.0 --port=%PORT% 