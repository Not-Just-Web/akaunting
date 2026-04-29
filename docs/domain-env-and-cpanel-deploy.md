# Domain Env + cPanel Deploy

This project now supports per-domain environment files without moving the `public` directory.

## 1) Document Root

Point your cPanel domain document root to the project root, for example:

- `akaunting.notjustweb.com` -> `/home/USER/akaunting.notjustweb.com`

Use root `index.php` and root `.htaccess`.

## 2) Per-domain env files

Create one env file per domain at project root:

- `.env.akaunting.notjustweb.com`
- `.env.anotherdomain.com`

Or place files in:

- `.env.domains/akaunting.notjustweb.com`
- `.env.domains/anotherdomain.com`

Runtime selection is based on request host.

Priority order:

1. `.env.{host}`
2. `.env.domains/{host}`
3. fallback `.env`

## 3) cPanel deploy workflow

Workflow file:

- `.github/workflows/deploy-cpanel.yml`

Required GitHub repository secrets:

- `CPANEL_HOST`
- `CPANEL_USER`
- `CPANEL_SSH_KEY`
- `CPANEL_PATH` (example: `/home/USER/akaunting.notjustweb.com`)

The deploy excludes `.env` and `.env.*` so each domain keeps its own credentials.

## 4) First-time setup on server

1. Upload/create domain env file, for example `.env.akaunting.notjustweb.com`
2. Ensure writable paths:
   - `storage`
   - `bootstrap/cache`
3. Run once:
   - `php artisan key:generate`
   - `php artisan migrate --force`

## 5) Notes

- Keep separate database credentials in each domain env file.
- For multi-domain hosting in one codebase, avoid checking `.env.*` into git.
