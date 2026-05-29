# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Peta BPS Enrekang** — a geographic census data management system for Indonesia's Central Statistics Agency (BPS). Manages a geographic hierarchy: Kecamatan (district) → Desa (village) → SLS (supervisory area) → Blok Sensus (census block), with activity tracking (Kegiatan) and map file management (KelolaPeta).

**Stack:** Laravel 12, PHP 8.2+, Vue 3, Inertia.js 2.0, Tailwind CSS 3.2, Vite 7

## Development Commands

```bash
# Initial setup
composer setup          # composer install + npm install + build + artisan commands

# Development (runs all services concurrently)
composer dev            # php artisan serve + queue:listen + pail + npm run dev

# Individual
php artisan serve       # API/web server on :8000
npm run dev             # Vite HMR dev server
php artisan queue:listen  # Required for Excel import/export jobs

# Build
npm run build           # Vite production build

# Tests
composer test           # php artisan test (PHPUnit, uses in-memory SQLite)
php artisan test --filter=TestName  # Run a specific test
```

## Required Setup Steps

After cloning, run in order:
1. `cp .env.example .env && php artisan key:generate`
2. Configure database in `.env` (MySQL, default port 3306)
3. `php artisan migrate`
4. `php artisan storage:link` — **required** to serve uploaded map files publicly
5. `composer setup` or manually: `composer install && npm install && npm run build`

## Architecture Notes

### Authentication & Roles
- Uses Laravel Sanctum with session-based auth (Breeze scaffolding)
- Three roles stored as strings in `users.role`: `ADMIN`, `IPDS`, `SUBJECT_MATTER`
- Custom `RoleMiddleware` protects routes — check `app/Http/Middleware/RoleMiddleware.php`
- Route groups in `routes/web.php` define which roles access which features

### Models & IDs
- All models use UUID primary keys (`char(36)`), not auto-increment integers
- Always use `Str::uuid()` when creating records manually
- UUID handling is in each model's boot method or `HasUuids` trait

### Inertia.js Pattern
- Single Blade entry point: `resources/views/app.blade.php`
- Vue components live in `resources/js/Pages/` (route-mapped) and `resources/js/Components/`
- Controllers return `Inertia::render('PageName/Component', [...props])`, not JSON
- JS path alias: `@/*` → `resources/js/*`

### Excel Import/Export
- Uses `maatwebsite/excel` for BlokSensus, Desa, SLS bulk operations
- Export classes: `app/Exports/`, Import classes: `app/Imports/`
- Imports may dispatch queue jobs — **queue worker must be running** (`php artisan queue:listen`)

### Map File Storage
- Map files (jpg/jpeg/png) are stored via the `local_uploads` disk
- **Note:** `local_uploads` disk is used in `KelolaPetaWilkerstatController` but not defined in `config/filesystems.php` — if you add a new disk, register it there
- Preview route: `GET /preview-peta/{filename}` serves map files publicly
- `php artisan storage:link` is required for the `public` disk symlink

## Code Style

Follow **Laravel Pint** defaults (PSR-12-based). Run before committing:
```bash
./vendor/bin/pint              # format all PHP files
./vendor/bin/pint app/         # format specific directory
```

Vue/JS: no ESLint configured; follow the existing component patterns in `resources/js/`.
