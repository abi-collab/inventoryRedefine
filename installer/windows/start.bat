@echo off
set ROOT=%~dp0..\..
cd /d "%ROOT%"
start "KYCC Inventory" php artisan serve --host=127.0.0.1 --port=8000
timeout /t 2 >nul
start http://127.0.0.1:8000
