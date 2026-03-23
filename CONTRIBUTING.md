# Contributing

Thanks for contributing to LaraTemp.

## Local Setup

1. Install dependencies.

```bash
composer install
npm install
```

2. Create your environment file and app key.

```bash
cp .env.example .env
php artisan key:generate
```

3. Configure your database, then run migrations and seeders.

```bash
php artisan migrate
php artisan db:seed
```

4. Start the app locally.

```bash
composer run dev
```

## Development Guidelines

- Keep changes focused and easy to review.
- Prefer reusable application logic over duplicated Blade or controller code.
- Do not commit real credentials, API keys, or secrets.
- Use environment variables for bootstrap admin credentials and other sensitive settings.
- Add or update tests when changing authentication, seeders, or behavior that affects users.

## Before Opening a Change

Run the basic project checks:

```bash
./vendor/bin/pint
php artisan test
npm run build
```

## Pull Request Notes

When opening a contribution, include:

- a short summary of the change
- why the change was needed
- any setup, migration, or environment notes
- test coverage added or updated

## Reporting Problems

- For security issues, follow [SECURITY.md](/home/kangketik/Projects/laratemp/SECURITY.md).
- For normal bugs or improvements, open an issue or submit a focused change.
