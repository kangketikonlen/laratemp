# Changelog

This file tracks notable project-level changes made during the recent cleanup and auth refactor work.

## 2026-03-24

### Project cleanup

- removed unused Composer packages: `laravel/sanctum` and `spatie/laravel-query-builder`
- removed unused frontend packages: `axios`, `autoprefixer`, and `postcss`
- removed empty Blade component PHP wrapper classes that were not adding behavior
- removed the old special-character login component file and replaced it with a standard structure
- kept `composer.json` and `package.json` focused on packages that are actually used

### Auth structure improvements

- introduced `app/Support/Auth/AuthPageData.php` to centralize auth-page metadata
- updated the auth controller and auth layout to use shared page data instead of duplicated Blade logic
- converted login into a standard Livewire component:
  - `app/Livewire/Auth/Login.php`
  - `resources/views/livewire/auth/login.blade.php`

### Security improvements

- removed hardcoded bootstrap admin credentials from the seeder
- added `config/bootstrap_admin.php` for configuration-driven bootstrap admin seeding
- updated `.env.example` with `BOOTSTRAP_ADMIN_*` variables
- changed the bootstrap admin seeder to skip seeding when no password is configured

### Testing improvements

- added Pest feature tests for login flow
- added a login rate-limit test to verify repeated failed attempts are blocked
- kept database seeding tests for roles, permissions, settings, and institution defaults

### Documentation improvements

- replaced the default Laravel README with project-specific documentation
- added this `CHANGELOGS.md` file to record notable changes
