# Smoke checklist (offline / local SQLite)

Run after install or major changes. App must work **without** Supabase connectivity.

- [ ] App starts (`php artisan serve` or installer service)
- [ ] Login with seeded/admin user (dev: `rey` / `admin123`)
- [ ] Dashboard loads (charts may be empty)
- [ ] Create / edit / list product
- [ ] Adjust stock
- [ ] POS: add to cart, complete order
- [ ] Two cashiers: carts stay isolated (login as `rey` and `ben` in separate browsers)
- [ ] Oversell blocked (cannot checkout more than available stock)
- [ ] Standard user cannot open Users or Sync now (admin-only)
- [ ] View order / search by date (ISO `ordered_on`)
- [ ] Customer / category CRUD
- [ ] Return: set status to `completed` restocks product + serial
- [ ] Logout / login again
- [ ] Manual sync command fails gracefully when Supabase is unset (`php artisan sync:supabase`)
- [ ] `php artisan backup:sqlite` copies DB into `storage/app/backups/`
- [ ] Backup copies `database/database.sqlite` (manual installer script still OK)
