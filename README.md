# Akaunting (Customized Self-Hosted Setup)

This repository is a customized Akaunting setup focused on self-hosted operation, local bank connector integrations, and cPanel-friendly deployment.

## Requirements

- PHP 8.1+
- Composer
- Node.js + npm
- MySQL/MariaDB (or supported Laravel database)
- Web server (Valet, Apache, Nginx, cPanel)

## Quick Start (Local)

1. Install dependencies:

```bash
composer install
npm install
```

2. Create local environment file:

```bash
cp .env.example .env
```

3. Set your database values in `.env`.

4. Build frontend assets:

```bash
npm run dev
```

5. Install app:

```bash
php artisan install --db-name="akaunting1" --db-username="root" --db-password="" --admin-email="studio@notjustweb.com" --admin-password="123456"
```

6. Clear caches:

```bash
php artisan optimize:clear
```

## How To Start Services

Use one of the following ways.

### Option A: Laravel Valet

```bash
valet link akaunting
valet secure akaunting
```

Open the app at the Valet URL shown by your local setup.

### Option B: Artisan server

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Open http://127.0.0.1:8000

### Frontend watch mode (optional)

```bash
npm run watch
```

## How To Test The App

### Automated tests

```bash
php artisan test
```

### Manual smoke test checklist

1. Login with admin credentials.
2. Open Banking -> Bank Connectors.
3. Save ConnectIPS/Basiq API settings.
4. Test Bank Link and statement sync actions.
5. Create incoming and outgoing transactions.
6. Open Reports and verify monthly totals.

## Troubleshooting Install (Exit Code 1)

If `php artisan install ...` fails with exit code `1`, run this checklist in order.

1. Verify PHP and extensions:

```bash
php -v
php -m | grep -E "mbstring|openssl|pdo|pdo_mysql|xml|ctype|json|bcmath|zip"
```

2. Verify database connectivity from Laravel env:

```bash
php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB OK';"
```

3. Clear stale caches:

```bash
php artisan optimize:clear
```

4. Ensure writable permissions:

```bash
chmod -R ug+rwx storage bootstrap/cache
```

5. Regenerate app key if missing:

```bash
php artisan key:generate
```

6. Run migrations directly to surface DB errors:

```bash
php artisan migrate:fresh --seed
```

7. Re-run installer with explicit options:

```bash
php artisan install --db-host="localhost" --db-port="3306" --db-name="akaunting1" --db-username="root" --db-password="" --admin-email="studio@notjustweb.com" --admin-password="123456"
```

8. If still failing, inspect latest logs:

```bash
tail -n 200 storage/logs/laravel.log
```

9. If setup was partially completed already, skip installer and use:

```bash
php artisan migrate --force
php artisan optimize:clear
```

## Features Added In This Workspace

1. Self-hosted mode with marketplace fallback removed.
2. Bank Connectors admin panel UI.
3. ConnectIPS integration (Nepal).
4. Basiq integration (Australian bank feeds).
5. Currency save fix for empty rate/default currency use case.
6. Root hosting support without moving `public/`.
7. Domain-based env resolution with fallback to default `.env`.
8. FTP deployment workflow for cPanel.
9. Lint + Prettier + Husky hooks.
10. Dummy/sample data cleanup command.

## Bank Connectors Setup

Go to Banking -> Bank Connectors.

Configure:

- ConnectIPS credentials and certificate paths.
- Basiq OAuth/API credentials.

Use:

- Bank Link
- Sync Statements

## Environment Resolution By Host

At runtime, env file resolution order is:

1. `.env-{hostname}`
2. `.env.{hostname}`
3. `.env.domains/{hostname}`
4. fallback `.env`

## Deployment (FTP / cPanel)

Workflow file:

- `.github/workflows/deploy-cpanel.yml`

Deploy exclude rules file:

- `.deploy-ignore`

Required GitHub variables/secrets:

- `vars.FTP_SERVER_STAGING`
- `vars.FTP_USERNAME_STAGING`
- `vars.FTP_SERVER_DIR_STAGING`
- `secrets.FTP_PASSWORD_STAGING`

## Code Quality And Hooks

Local commands:

- `npm run lint`
- `npm run lint:fix`
- `npm run format`
- `npm run format:check`

Git hooks:

- `.husky/pre-commit` -> `npx lint-staged`
- `.husky/pre-push` -> `npm run lint`

CI workflow:

- `.github/workflows/quality.yml`

## Cleanup Dummy Data

Dry run:

```bash
php artisan data:clear-dummy --dry-run --admin-email=studio@notjustweb.com
```

Execute:

```bash
php artisan data:clear-dummy --admin-email=studio@notjustweb.com --force
```

## AI Build Records

All agent-created build notes are stored in:

- `ai/AGENT_FEATURES_AND_STEPS.md`
