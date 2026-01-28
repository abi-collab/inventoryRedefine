# Database Seeding Guide

## Seeders Run Successfully ✅

Your database has been populated with sample data!

### Data Added:

| Table | Records |
|-------|---------|
| Users | 2 |
| Employees | 3 |
| Suppliers | 3 |
| Categories | 3 |
| Products | 5 |
| Customers | 3 |

## How Seeders Work

The seeders are located in: `database/seeds/`

Available seeders:
- `UsersTableSeeder.php` - Admin users
- `EmployeeTableSeeder.php` - Employee records
- `SuppliersTableSeeder.php` - Supplier information
- `CategoriesTableSeeder.php` - Product categories
- `ProductsTableSeeder.php` - Product data
- `CustomersTableSeeder.php` - Customer information

## Running Seeders

### Run All Seeders (Recommended)
```bash
php artisan db:seed
```
This runs the `DatabaseSeeder.php` which calls all seeders in order.

### Run Specific Seeder
```bash
php artisan db:seed --class=UsersTableSeeder
```

### Run Seeders Fresh with Migrations
```bash
php artisan migrate:fresh --seed
```
⚠️ **Warning**: This will DROP all tables and recreate them!

### Run Seeders Multiple Times
```bash
php artisan db:seed --force
```

## Default Login Credentials

After seeding, you can use these credentials to login:

**Admin User:**
- Email: `admin@example.com`
- Password: Check UsersTableSeeder.php or set your own

Check the seeder files for specific user credentials if needed.

## Customizing Seeders

To modify the seeded data, edit the seeder files:
```
database/seeds/
├── UsersTableSeeder.php
├── EmployeeTableSeeder.php
├── SuppliersTableSeeder.php
├── CategoriesTableSeeder.php
├── ProductsTableSeeder.php
├── CustomersTableSeeder.php
└── DatabaseSeeder.php
```

## Clearing Data

To remove all seeded data and start fresh:

```bash
# Clear specific table
php artisan tinker
>>> DB::table('users')->truncate();
>>> exit;

# Or run fresh migrations
php artisan migrate:fresh --seed
```

---
**Last Updated**: January 29, 2026
**Status**: ✅ Database Seeded Successfully
