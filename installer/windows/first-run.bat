@echo off
setlocal
set ROOT=%~dp0..\..
cd /d "%ROOT%"

if not exist .env copy .env.example .env
if not exist database\database.sqlite type nul > database\database.sqlite

php artisan key:generate --force
php -r "$e=file_get_contents('.env');$e=preg_replace('/^DB_CONNECTION=.*/m','DB_CONNECTION=sqlite',$e);$e=preg_replace('/^DB_DATABASE=.*/m','DB_DATABASE='.str_replace('\\','/',getcwd()).'/database/database.sqlite',$e);file_put_contents('.env',$e);"

php artisan migrate --force
php artisan db:seed --force
php artisan jwt:secret --force

echo.
echo First-run complete. Use start.bat to launch the app.
echo Register the scheduler with register-scheduler.bat (as Administrator if needed).
pause
