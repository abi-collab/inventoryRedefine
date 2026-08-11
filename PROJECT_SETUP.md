# KYCC Inventory — Setup

Local-first Laravel 11 + Vue 3 POS. See [docs/INSTALL.md](docs/INSTALL.md) for full install & Supabase sync, and [docs/SMOKE_CHECKLIST.md](docs/SMOKE_CHECKLIST.md) for verification.

## Stack

- Backend: Laravel 11, JWT auth, SQLite (local), optional Supabase Postgres sync
- Frontend: Vue 3, Vite, Pinia, Vue Router 4, Tailwind CSS
- Daily sync: `php artisan sync:supabase` via OS scheduler

## Dev

```bash
composer install
cp .env.example .env && php artisan key:generate && php artisan jwt:secret
touch database/database.sqlite
# set DB_CONNECTION=sqlite and DB_DATABASE to absolute path of database.sqlite
php artisan migrate --force && php artisan db:seed --force
npm install
npm run dev   # or npm run build
php artisan serve
```

Open http://127.0.0.1:8000

## Seeded users (dev-only)

See `database/seeders/UsersTableSeeder.php`:

| Username | Password  | Role    |
|----------|-----------|---------|
| rey      | admin123  | Admin   |
| ben      | user1234  | Standard|

Change these immediately on any shop install. Public signup is disabled; admins create users in-app.
