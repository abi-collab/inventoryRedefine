#!/usr/bin/env bash
# First-run / local bootstrap for KYCC Inventory (macOS/Linux)
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
cd "$ROOT"

if [[ ! -f .env ]]; then
  cp .env.example .env
  php artisan key:generate
fi

if [[ ! -f database/database.sqlite ]]; then
  touch database/database.sqlite
fi

# Ensure sqlite path
php -r '
$env=file_get_contents(".env");
if (!preg_match("/^DB_CONNECTION=sqlite/m", $env)) {
  $env=preg_replace("/^DB_CONNECTION=.*/m","DB_CONNECTION=sqlite",$env);
}
if (!preg_match("/^DB_DATABASE=/m", $env)) {
  $env.="\nDB_DATABASE=".getcwd()."/database/database.sqlite\n";
} else {
  $env=preg_replace("/^DB_DATABASE=.*/m","DB_DATABASE=".getcwd()."/database/database.sqlite",$env);
}
file_put_contents(".env",$env);
'

php artisan migrate --force
php artisan db:seed --force || true
php artisan backup:sqlite --keep=14 || true

if [[ -z "${JWT_SECRET:-}" ]] && ! grep -q '^JWT_SECRET=.\+' .env; then
  php artisan jwt:secret --force || true
fi

echo "Bootstrap complete. Start with: installer/unix/start.sh"
echo "Daily SQLite backup runs via scheduler at 01:30 (backup:sqlite)."
