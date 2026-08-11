@echo off
set ROOT=%~dp0..\..
cd /d "%ROOT%"
set STAMP=%date:~10,4%-%date:~4,2%-%date:~7,2%_%time:~0,2%-%time:~3,2%
set STAMP=%STAMP: =0%
set DEST=%USERPROFILE%\Documents\KYCC-Backups
if not exist "%DEST%" mkdir "%DEST%"
set ZIP=%DEST%\kycc_%STAMP%.zip

powershell -NoProfile -Command "Compress-Archive -Path 'database\database.sqlite','storage\app','storage\logs' -DestinationPath '%ZIP%' -Force"
echo Backup written to %ZIP%
pause
