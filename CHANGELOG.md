# Changelog

This file tracks notable project-level changes made during the recent cleanup and auth refactor work.

## 2026-03-24

### Role and module workspace

- added database support for role metadata, modules, and module navigation items
- moved role and module models into `app/Models/Settings`
- seeded a role-based workspace structure where `General Settings` is the main module entry
- added module navigation with these primary sections:
  - `Master`
  - `Settings`
  - `Administration`
  - `Report`
- added sub navigation items for:
  - `Master`: `User`, `Role`
  - `Settings`: `Institution`, `Permission`
  - `Administration`: `Changelogs`, `Work Progress`
  - `Report`: `Activity Log`, `Error Report`
- updated the dashboard to show assigned modules as landing cards
- added a dedicated module dashboard flow and section dashboards
- split signed-in layouts into role-level and module-level workspace shells
- refactored repeated private UI into reusable Blade components and named CSS utilities
- added and updated feature tests covering module navigation, sub navigation, and seeded workspace structure

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
- added this `CHANGELOG.md` file to record notable changes
