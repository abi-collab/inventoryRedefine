@echo off
REM Registers Task Scheduler to run Laravel schedule:run every minute.
REM Daily sync:supabase is defined in app/Console/Kernel.php (02:00).
set ROOT=%~dp0..\..
cd /d "%ROOT%"
set PHP=php

schtasks /Create /F /TN "KYCC Inventory Scheduler" /SC MINUTE /MO 1 /TR "\"%PHP%\" \"%CD%\artisan\" schedule:run" /RL LIMITED
echo Task created: KYCC Inventory Scheduler
pause
