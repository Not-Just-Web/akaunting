# Akaunting™

[![Release](https://img.shields.io/github/v/release/akaunting/akaunting?label=release)](https://github.com/akaunting/akaunting/releases)
![Downloads](https://img.shields.io/github/downloads/akaunting/akaunting/total?label=downloads)
[![Translations](https://badges.crowdin.net/akaunting/localized.svg)](https://crowdin.com/project/akaunting)
[![Tests](https://img.shields.io/github/actions/workflow/status/akaunting/akaunting/tests.yml?label=tests)](https://github.com/akaunting/akaunting/actions)

Online accounting software designed for small businesses and freelancers. Akaunting is built with modern technologies such as Laravel, VueJS, Tailwind, RESTful API etc. Thanks to its modular structure, Akaunting provides an awesome App Store for users and developers.

* [Home](https://akaunting.com) - The house of Akaunting
* [Forum](https://akaunting.com/forum) - Ask for support
* [Documentation](https://akaunting.com/hc/docs) - Learn how to use
* [Developer Portal](https://developer.akaunting.com) - Generate passive income
* [App Store](https://akaunting.com/apps) - Extend your Akaunting
* [Translations](https://crowdin.com/project/akaunting) - Help us translate Akaunting

## Requirements

* PHP 8.1 or higher
* Database (e.g.: MariaDB, MySQL, PostgreSQL, SQLite)
* Web Server (eg: Apache, Nginx, IIS)
* [Other libraries](https://akaunting.com/hc/docs/on-premise/requirements/)

## Framework

Akaunting uses [Laravel](http://laravel.com), the best existing PHP framework, as the foundation framework and [Module](https://github.com/akaunting/module) package for Apps.

## Installation

Before installing Akaunting, make sure your environment has the required dependencies installed:

* PHP 8.1 or higher with the required PHP extensions
* Composer
* Node.js and npm
* Git
* A supported database server, such as MariaDB, MySQL, PostgreSQL, or SQLite
* A web server, such as Apache, Nginx, or IIS
* Build tools required by some npm packages, such as `build-essential` on Debian/Ubuntu systems

For the full list of PHP extensions and server requirements, see the [on-premise requirements](https://akaunting.com/hc/docs/on-premise/requirements/).

Then install Akaunting:

* Clone the repository: `git clone https://github.com/akaunting/akaunting.git`
* Install dependencies: `composer install ; npm install ; npm run dev`
* Install Akaunting:

```bash
php artisan install --db-name="akaunting" --db-username="root" --db-password="pass" --admin-email="admin@company.com" --admin-password="123456"
```

* Create sample data (optional): `php artisan sample-data:seed`

## Local Development (This Workspace)

Use these steps for this customized self-hosted setup:

1. Copy environment file:
	- `cp .env.example .env`
2. Install dependencies:
	- `composer install`
	- `npm install`
	- `npm run dev`
3. Configure database in `.env`.
4. Install app:
	- `php artisan install --db-name="akaunting1" --db-username="root" --db-password="" --admin-email="studio@notjustweb.com" --admin-password="123456"`
5. Clear caches after config changes:
	- `php artisan optimize:clear`

Optional cleanup of seeded or dummy transactional data while keeping admin login:

- Dry run: `php artisan data:clear-dummy --dry-run --admin-email=studio@notjustweb.com`
- Execute: `php artisan data:clear-dummy --admin-email=studio@notjustweb.com --force`

## Feature Enhancements Added

This workspace includes custom enhancements beyond upstream defaults:

1. Self-hosted mode (marketplace routes blocked)
2. Bank Connectors admin UI (API setup + one-click bank link)
3. ConnectIPS integration (Nepal)
4. Basiq integration (Australian bank feeds)
5. Currency save fix for empty rate/default currency (NPR use case)
6. Root hosting support without moving `public/`
7. Domain-based environment file resolution
8. cPanel GitHub Actions deployment workflow

## Bank Connector Setup

Go to Banking -> Bank Connectors in admin panel.

Configure:

- ConnectIPS: base URL, merchant/app IDs, credentials, key paths
- Basiq: OAuth URLs, client ID/secret, redirect URI, statements path

Then use:

- **Bank Link** for authorization/linking
- **Sync Statements** for feed retrieval

## Domain-Based Environment Files

When serving by host, environment file loading order is:

1. `.env-{hostname}`
2. `.env.{hostname}`
3. `.env.domains/{hostname}`
4. default `.env` fallback when no host-specific file exists

This enables one codebase with separate per-domain DB credentials.

## Deployment To cPanel

Workflow file:

- `.github/workflows/deploy-cpanel.yml`

Required GitHub secrets:

- `CPANEL_HOST`
- `CPANEL_USER`
- `CPANEL_SSH_KEY`
- `CPANEL_PATH`

## AI Build Records

All agent-created feature and step documentation is recorded in:

- `ai/AGENT_FEATURES_AND_STEPS.md`

## Contributing

Please, be very clear on your commit messages and Pull Requests, empty Pull Request messages may be rejected without reason.

When contributing code to Akaunting, you must follow the PSR coding standards. The golden rule is: Imitate the existing Akaunting code.

Please note that this project is released with a [Contributor Code of Conduct](https://akaunting.com/conduct). *By participating in this project you agree to abide by its terms*.

## Translation

If you'd like to contribute translations, please check out our [Crowdin](https://crowdin.com/project/akaunting) project.

## Changelog

Please see [Releases](../../releases) for more information about what has changed recently.

## Security

Please review [our security policy](https://github.com/akaunting/akaunting/security/policy) on how to report security vulnerabilities.

## Credits

* [Denis Duliçi](https://github.com/denisdulici)
* [Cüneyt Şentürk](https://github.com/cuneytsenturk)
* [All Contributors](../../contributors)

## License

Akaunting is released under the [BSL license](LICENSE.txt).
