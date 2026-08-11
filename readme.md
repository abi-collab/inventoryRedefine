# KYCC Inventory (POS)

Local-first Point of Sale for KYCC — **Laravel 11 + Vue 3 + Vite**, SQLite on the shop PC, optional daily mirror to Supabase Postgres.

## Features

- JWT auth with admin / standard roles
- Multi-cashier carts (isolated per user)
- Products, stock, serial numbers, customers, suppliers
- POS checkout with atomic stock updates
- Orders, returns (completed returns restock), expenses, audit log
- Optional Supabase sync (`sync:supabase`) with delete tombstones
- Daily SQLite backup (`backup:sqlite`)

## Quick start

See **[PROJECT_SETUP.md](PROJECT_SETUP.md)** and **[docs/INSTALL.md](docs/INSTALL.md)**.

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

Open http://127.0.0.1:8000

Dev-only seeded users are documented in `PROJECT_SETUP.md` — change them on any shop install.

## Verify

Use **[docs/SMOKE_CHECKLIST.md](docs/SMOKE_CHECKLIST.md)** after install or upgrades.
