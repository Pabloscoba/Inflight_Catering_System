@echo off
echo Clearing all Laravel caches...
cd /d C:\Users\ramad\Inflight_Catering_System
call php artisan route:clear
call php artisan view:clear
call php artisan cache:clear
call php artisan config:clear
call php artisan optimize:clear
echo.
echo All caches cleared!
echo.
pause
