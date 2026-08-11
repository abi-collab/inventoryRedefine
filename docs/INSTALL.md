# Local install & Supabase sync

## Architecture

- **Local SQLite** is the POS source of truth (works offline).
- **Supabase Postgres** is a daily cloud mirror for backup/reporting.
- Auth remains **Laravel JWT** (not Supabase Auth).

## Developer quick start

```bash
cp .env.example .env
touch database/database.sqlite
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate --force
php artisan db:seed --force
npm install
npm run build   # or npm run dev
php artisan serve
```

Open http://127.0.0.1:8000 — default seeded users in `database/seeders/UsersTableSeeder.php`.

## Shop PC install (Windows)

1. Install PHP 8.2+ with extensions: `pdo_sqlite`, `pdo_pgsql`, `openssl`, `mbstring`, `fileinfo`.
2. Copy the app folder (with `vendor/` and built `public/build/` from `npm run build`).
3. Run `installer\windows\first-run.bat`.
4. Run `installer\windows\start.bat` (or install WinSW using `kycc-inventory-service.xml`).
5. Run `installer\windows\register-scheduler.bat` so `schedule:run` executes every minute (daily `sync:supabase` at 02:00).
6. Optional backup: `installer\windows\backup.bat`.

## macOS / Linux

```bash
chmod +x installer/unix/*.sh
./installer/unix/first-run.sh
./installer/unix/start.sh
# scheduler:
./installer/unix/install-scheduler-macos.sh
# or
./installer/unix/install-scheduler-linux.sh
```

## Configure Supabase sync

1. Create a Supabase project.
2. Create schema `laravel` in the SQL editor: `create schema if not exists laravel;`
3. Put Session Pooler credentials in `.env`:

```env
SUPABASE_SYNC_ENABLED=true
SUPABASE_DB_URL=postgres://postgres.[REF]:[PASSWORD]@aws-[REGION].pooler.supabase.com:5432/postgres
SUPABASE_DB_SCHEMA=laravel
SUPABASE_DB_SSLMODE=require
```

4. Create remote tables once:

```bash
php artisan migrate --database=supabase --force
```

5. Test:

```bash
php artisan sync:supabase --force
# or full resync
php artisan sync:supabase --force --full
```

6. In-app **Sync now** (admin header) calls `POST /api/sync/now`.

## Notes

- Cart table `pos` is **not** synced (ephemeral).
- Live POS never depends on Supabase availability.
- Treat `database/migrations` as schema source of truth; `inventory_vue.sql` is legacy sample only.
