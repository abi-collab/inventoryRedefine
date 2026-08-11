# Smoke checklist (offline / local SQLite)

Run after install or major changes. App must work **without** Supabase connectivity.

- [ ] App starts (`php artisan serve` or installer service)
- [ ] Login with seeded/admin user
- [ ] Dashboard loads (charts may be empty)
- [ ] Create / edit / list product
- [ ] Adjust stock
- [ ] POS: add to cart, complete order
- [ ] View order / search by date
- [ ] Customer / category CRUD
- [ ] Logout / login again
- [ ] Manual sync command fails gracefully when Supabase is unset (`php artisan sync:supabase`)
- [ ] Backup copies `database/database.sqlite`
