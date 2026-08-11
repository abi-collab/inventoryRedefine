#!/usr/bin/env bash
# Cron entry for Laravel scheduler (Linux)
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
PHP="$(command -v php)"
LINE="* * * * * cd ${ROOT} && ${PHP} artisan schedule:run >> /dev/null 2>&1"
(crontab -l 2>/dev/null | grep -v 'artisan schedule:run'; echo "$LINE") | crontab -
echo "Cron installed for schedule:run every minute."
