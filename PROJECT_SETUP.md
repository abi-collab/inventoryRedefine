# Inventory Management System - Setup & Run Guide

## Project Overview
This is a **Vue.js + Laravel** project for an Inventory Management System (KYCC POS & Inventory).

## Current Status ✅
- **Laravel Framework**: 11.48
- **PHP Version**: 8.5.2
- **Vue.js Version**: 2.7.16
- **Node.js**: Available
- **Database**: MySQL (kyccdb)
- **Build Tool**: Laravel Mix (Webpack)

## Running the Project

### Quick Start (Recommended)
Open two terminal windows/tabs:

#### Terminal 1: Start Laravel Server
```bash
cd /Users/abinadi/Documents/GitHub/inventoryRedefine
php artisan serve
```
This starts the server on: **http://127.0.0.1:8000**

#### Terminal 2: Start Vue.js Development Build
```bash
cd /Users/abinadi/Documents/GitHub/inventoryRedefine
npm run watch
```
This automatically recompiles Vue components and assets when you make changes.

### Alternative: One-Command Setup
```bash
# Terminal 1
php artisan serve > /tmp/laravel.log 2>&1 &

# Terminal 2
npm run watch > /tmp/npm-watch.log 2>&1 &
```

## Available NPM Commands

| Command | Purpose |
|---------|---------|
| `npm run dev` | One-time development build |
| `npm run watch` | Watch mode - auto-recompiles on changes (RECOMMENDED) |
| `npm run prod` | Production build with optimization |
| `npm run hot` | Hot reload (browser auto-refreshes) |

## Available Artisan Commands

| Command | Purpose |
|---------|---------|
| `php artisan serve` | Start development server |
| `php artisan migrate` | Run database migrations |
| `php artisan tinker` | Interactive PHP shell |
| `php artisan view:clear` | Clear compiled views cache |
| `php artisan cache:clear` | Clear application cache |

## Project Structure

```
resources/
  ├── js/
  │   ├── app.js          # Vue.js entry point
  │   └── components/     # Vue components
  ├── sass/
  │   └── app.scss        # Styles
  └── views/
      └── welcome.blade.php  # Main view

storage/
  └── logs/               # Application logs

config/
  ├── database.php        # Database config
  └── app.php            # App config

database/
  └── migrations/        # Database schemas

routes/
  ├── web.php           # Web routes
  └── api.php           # API routes
```

## Key Features

- **Frontend**: Vue.js 2 SPA (Single Page Application)
- **Backend**: Laravel 11 REST API
- **Database**: MySQL
- **Build System**: Laravel Mix (Webpack 4)
- **Styling**: Bootstrap 4 + SASS
- **Charts**: ApexCharts for data visualization
- **Excel Export**: Vue JSON Excel
- **PDF Generation**: HTML2Canvas + jsPDF

## Troubleshooting

### Port 8000 is already in use
```bash
# The server will automatically use port 8001
php artisan serve
```

### Vue components not updating
```bash
# Clear view cache and restart npm watch
php artisan view:clear
npm run watch
```

### Database connection error
Check `.env` file:
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kyccdb
DB_USERNAME=root
DB_PASSWORD=
```

### Build errors
```bash
# Clean install dependencies
npm install --legacy-peer-deps
npm run dev
```

## Performance Tips

1. **During Development**: Use `npm run watch` to auto-compile
2. **For Production**: Run `npm run prod` for optimized build
3. **Clear Cache**: Regularly use `php artisan cache:clear`
4. **Database Optimization**: Ensure proper indexes on frequently queried columns

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js 2 Documentation](https://v2.vuejs.org)
- [Laravel Mix Documentation](https://laravel-mix.com)

---
**Last Updated**: January 29, 2026
**Project Status**: ✅ Ready for Development
