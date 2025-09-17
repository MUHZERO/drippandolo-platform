# Drippandolo Platform

Filament-powered admin panel for managing orders, revenues, spends, and fornissure invoices with role-based access (admin, operator, fornissure), notifications, and a dashboard.

## Local Development

- Copy `.env.example` to `.env` and set your database credentials.
- Install PHP dependencies: `composer install`
- Install JS deps: `npm install`
- Generate app key: `php artisan key:generate`
- Run migrations: `php artisan migrate`
- Start dev: `composer run dev` (serves app, queue listener, logs, and Vite)

## Tests

Run `composer test`.
