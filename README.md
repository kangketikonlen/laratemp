# LaraTemp

LaraTemp is a Laravel 13 starter focused on a simple authenticated entry flow, reusable app settings, and a cleaner baseline for building internal applications.

It currently includes:

- username-based authentication with a Livewire login form
- login rate limiting for repeated failed attempts
- role and permission seeding with `spatie/laravel-permission`
- app and institution settings via `spatie/laravel-settings`
- a seeded bootstrap admin that is configuration-driven instead of hardcoded
- Pest feature tests for login and database seeding

## Stack

- PHP 8.3+
- Laravel 13
- Livewire 4
- Tailwind CSS 4
- Vite 8
- Pest 4

## Project Structure

Key areas in the project:

- `app/Actions/Auth` contains auth-related actions
- `app/Livewire/Auth` contains the login component
- `app/Support/Auth` contains reusable auth page metadata helpers
- `database/seeders/Auth` contains role and bootstrap-admin seeders
- `database/seeders/Settings` contains default settings and institution seeders
- `resources/views/auth` contains auth page views
- `resources/views/livewire/auth` contains Livewire Blade views
- `tests/Feature/Auth` contains login feature coverage
- `tests/Feature/Database` contains seeder coverage

## Getting Started

### 1. Install dependencies

```bash
composer install
npm install
```

### 2. Prepare environment

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure database

Update your `.env` database values, then run:

```bash
php artisan migrate
```

### 4. Optional bootstrap admin

The bootstrap admin is controlled by environment variables and is not seeded unless a password is provided.

Add these to `.env` if you want a seeded admin user:

```env
BOOTSTRAP_ADMIN_ENABLED=true
BOOTSTRAP_ADMIN_NAME="Administrator"
BOOTSTRAP_ADMIN_EMAIL=admin@example.com
BOOTSTRAP_ADMIN_USERNAME=support
BOOTSTRAP_ADMIN_PASSWORD=change-this-password
```

If `BOOTSTRAP_ADMIN_PASSWORD` is empty, the admin seeder is skipped.

### 5. Seed data

```bash
php artisan db:seed
```

### 6. Run the app

For separate processes:

```bash
php artisan serve
npm run dev
```

Or use the Composer dev script:

```bash
composer run dev
```

## Useful Commands

```bash
composer run test
php artisan test
npm run build
./vendor/bin/pint
```

## Authentication Notes

- The app redirects `/` to `/login`.
- Login uses `username` and `password`.
- Failed attempts are rate limited per username and IP address.
- Successful login redirects to `/dashboard`.

## Testing

Current feature coverage includes:

- root redirect to login
- successful login with seeded admin credentials
- invalid login rejection
- repeated failed login rate limiting
- role, permission, institution, and settings seeding

Run the test suite with:

```bash
php artisan test
```

## Packages In Use

Primary runtime packages:

- `livewire/livewire`
- `spatie/laravel-activitylog`
- `spatie/laravel-data`
- `spatie/laravel-permission`
- `spatie/laravel-settings`

Primary development packages:

- `barryvdh/laravel-debugbar`
- `barryvdh/laravel-ide-helper`
- `laravel/pail`
- `laravel/pint`
- `pestphp/pest`
- `phpunit/phpunit`

## Notes

- The auth page content is centralized through `AuthPageData` for reuse.
- The login flow uses a standard Livewire class and Blade view pair.
- The repo includes a `CHANGELOG.md` file for project-level change tracking.
