# Agent Features And Steps Log

This file records features and implementation steps added by the AI agent in this workspace.

## 1) Self-hosted / No Marketplace Mode

Implemented to avoid external marketplace dependency and keep app self-sufficient.

### Changes
- Blocked Apps marketplace routes with middleware:
  - `app/Http/Middleware/RedirectMarketplaceToLocal.php`
  - `app/Http/Kernel.php` middleware alias: `offline.apps`
  - `routes/admin.php` apps route group now uses `offline.apps`
- Removed Apps menu entry:
  - `app/Listeners/Menu/ShowInAdmin.php`
- Replaced banking import app-store fallback links with local pages:
  - `resources/views/banking/transactions/import.blade.php`

## 2) Bank Connectors Hub (Admin)

Added native connector hub in admin panel for API setup and quick bank link actions.

### Routes
- `bank-connectors.index`
- `bank-connectors.connectips.settings`
- `bank-connectors.connectips.link`
- `bank-connectors.connectips.return`
- `bank-connectors.connectips.validate`
- `bank-connectors.basiq.settings`
- `bank-connectors.basiq.connect`
- `bank-connectors.basiq.callback`
- `bank-connectors.basiq.statements`
- `bank-connectors.basiq.disconnect`

### Files
- Controller: `app/Http/Controllers/Banking/BankConnectors.php`
- Views:
  - `resources/views/banking/connectors/index.blade.php`
  - `resources/views/banking/connectors/connectips-redirect.blade.php`
- Services:
  - `app/Services/BankConnectors/ConnectIpsService.php`
  - `app/Services/BankConnectors/BasiqService.php`
- Config:
  - `config/services.php`
  - `.env`
  - `.env.example`

## 3) ConnectIPS (Nepal)

Implemented payment gateway token-signing and validation flow.

### Steps
1. Configure ConnectIPS credentials in Bank Connectors admin page.
2. Upload key files into `storage/app/connectips/`:
   - `private_key.pem`
   - `certificate.pem`
3. Click **Bank Link**.
4. Use **Validate Statement Link** with reference ID and amount.

## 4) Australian Bank Support via Basiq

Implemented OAuth link and statement sync using one aggregator.

### Steps
1. Configure Basiq credentials in Bank Connectors admin page.
2. Set OAuth callback URL to:
   - `/banking/bank-connectors/basiq/callback`
3. Click **Bank Link**.
4. Click **Sync Statements**.

## 5) Currency Fix (NPR / Default Currency)

Fixed issue where saving currency failed with `The rate field is required`.

### Changes
- `app/Http/Requests/Setting/Currency.php`
  - Adds `prepareForValidation()` to default empty `rate` to `1`.
  - Changes validation from `required|gt:0` to `nullable|gt:0`.
- `resources/assets/js/views/settings/currencies.js`
  - Sets fallback rate to `1` if no rate is returned by config endpoint.

## 6) Dummy Data Cleanup Command

Added new artisan command to remove transactional/sample data while keeping admin login.

### Command
- `php artisan data:clear-dummy --admin-email=studio@notjustweb.com --force`
- Dry run:
  - `php artisan data:clear-dummy --dry-run --admin-email=studio@notjustweb.com`

### File
- `app/Console/Commands/ClearDummyData.php`

## 7) Root Hosting Without Moving public/

Enabled serving from project root for cPanel-style doc root.

### Changes
- Root front controller updated:
  - `index.php`
- Root rewrite rules in place:
  - `.htaccess`
- Domain env auto-load logic:
  - `bootstrap/app.php`

## 8) Multi-Domain Env Resolution

Supported host-based env files:
- `.env-{hostname}`
- `.env.{hostname}`
- `.env.domains/{hostname}`
- Fallback: default `.env` when none found.

## 9) cPanel GitHub Actions Deploy

Added deploy workflow for cPanel SSH/rsync deployment.

### File
- `.github/workflows/deploy-cpanel.yml`

### Required GitHub Secrets
- `CPANEL_HOST`
- `CPANEL_USER`
- `CPANEL_SSH_KEY`
- `CPANEL_PATH`

## 10) Operational Notes

- Clear app caches after changes:
  - `php artisan optimize:clear`
- Connector settings can now be managed in admin UI instead of only `.env`.
