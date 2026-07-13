<h1 align="center">SEOMaker</h1>

<p align="center">
  <em>A lightweight, framework-free PHP CMS with built-in SEO tooling</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-%3E%3D8.0-111111?style=flat-square" alt="PHP 8.0 or higher">
  <img src="https://img.shields.io/badge/database-SQLite-111111?style=flat-square" alt="SQLite database">
  <img src="https://img.shields.io/badge/framework-none-111111?style=flat-square" alt="No framework">
  <img src="https://img.shields.io/badge/license-unspecified-111111?style=flat-square" alt="License unspecified">
</p>

SEOMaker is a small, self-contained CMS and landing-page template for building SEO-optimized websites. It ships with an admin panel, a visual block editor, and a full SEO engine — meta tags, Schema.org JSON-LD, sitemap, robots.txt, and redirects — all in plain PHP with zero framework dependencies and a single SQLite file as the database.

## Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Getting Started](#getting-started)
- [Admin Access](#admin-access)
- [Usage](#usage)
- [Configuration](#configuration)
- [Project Structure](#project-structure)
- [Testing](#testing)
- [Spec-Driven Workflow](#spec-driven-workflow)
- [Contributing](#contributing)
- [License](#license)

## Features

- **SEO toolkit:** per-page meta title, description and canonical URL, Open Graph and Twitter Card tags, Schema.org JSON-LD (WebSite, WebPage, Organization, LocalBusiness, BreadcrumbList), Google Analytics gtag injection, dynamic `sitemap.xml`, and `robots.txt` with custom rules.
- **Admin panel:** session-protected dashboard at `/admin` with site settings organized in tabs, page CRUD, a media gallery with drag-and-drop upload, a contact message inbox, and a 301/302 redirects manager.
- **Block editor:** visual JSON-based page builder with text, image, gallery, video, map, CTA, FAQ, and spacer blocks — plus an SEO/SERP preview and a layperson-friendly schema form.
- **Contact form:** validated submissions with per-IP rate limiting, persisted to the database, and delivered by email over SMTP (PHPMailer) with a native `mail()` fallback.
- **Media management:** image uploads (JPG, PNG, GIF, WEBP, up to 5 MB) organized into `public/uploads/YYYY/MM/`, reusable from the block editor.
- **Zero infrastructure:** one SQLite file, no `.env`, no build step. All settings live in the database and are edited from the admin panel.

## Tech Stack

- [PHP](https://www.php.net/) 8.0+: language (no framework — hand-rolled router, view layer, and autoloader)
- [SQLite](https://www.sqlite.org/): database, via PDO
- [PHPMailer](https://github.com/PHPMailer/PHPMailer) ^6.9: the only runtime dependency, for SMTP mail
- Vanilla JS and CSS: admin and public front-ends, no build tooling

## Requirements

- PHP **8.0** or higher, with extensions: `pdo_sqlite`, `fileinfo`, `session`, `mbstring`
- `gd` extension (optional — used for media thumbnails)
- [Composer](https://getcomposer.org/)
- Node.js (optional — only to run the JS test)

## Getting Started

```bash
git clone https://github.com/leorsousa05/SEOMaker.git
cd SEOMaker
composer install
mkdir -p config
php -S localhost:8000 -t public public/router.php
```

Open [http://localhost:8000](http://localhost:8000). The SQLite database (`config/database.sqlite`) is created and seeded automatically on the first request — including a homepage, an "about" page, and the default admin user.

> [!TIP]
> The `config/` and `public/uploads/` directories are gitignored and created at runtime. Deploying to Apache instead? Point the document root at `public/` — the bundled `.htaccess` rewrites all requests to `index.php`.

## Admin Access

Log in at [http://localhost:8000/admin/login](http://localhost:8000/admin/login):

| Field | Default value |
|-------|---------------|
| Username | `admin` |
| Password | `admin123` |

> [!WARNING]
> The default credentials are seeded on first run for local setup only. Change the admin password immediately on any instance reachable from the network.

## Usage

From the admin dashboard you can:

1. **Create a page** — `/admin/pages` → add a slug, then compose content with the block editor (text, images, galleries, videos, maps, CTAs, FAQs). Each page gets its own SEO fields and a live SERP preview.
2. **Configure the site** — `/admin/settings` → site title, description, URL, contact email, logo, OG image, Google Analytics ID, and custom `robots.txt` rules.
3. **Set up email** — `/admin/settings` → SMTP host, port, user, and password. When configured, contact-form notifications go out via PHPMailer over SMTP; otherwise the native `mail()` function is used.
4. **Upload media** — `/admin/media` → drag and drop images, then insert them into any page through the block editor's media modal.
5. **Manage redirects** — `/admin/redirects` → add 301/302 rules, evaluated before routing on every public request.
6. **Read contact messages** — `/admin/messages` → inbox with reply, archive, and delete actions.

SEO output is automatic: visit any public page and inspect the `<head>` for meta tags and JSON-LD, or fetch `/sitemap.xml` and `/robots.txt` directly.

## Configuration

SEOMaker has no `.env` file and no config files. Every setting is a row in the SQLite `settings` table, edited through `/admin/settings`:

| Key | What it controls |
|-----|------------------|
| `site_title`, `site_description`, `site_url` | Global identity and canonical base URL |
| `contact_email` | Recipient for contact-form notifications |
| `mail_from`, `mail_from_name` | Sender identity for outgoing mail |
| `smtp_host`, `smtp_port`, `smtp_user`, `smtp_pass` | SMTP delivery (PHPMailer); empty = `mail()` fallback |
| `og_image`, `site_logo` | Default Open Graph image and site logo |
| `analytics_id` | Google Analytics measurement ID (gtag) |
| `robots_txt_custom` | Extra rules appended to `robots.txt` |

## Project Structure

```
├── public/            # Web root: index.php entry point, router.php (built-in
│                      # server), .htaccess (Apache), assets/, uploads/
├── src/               # Application code (PSR-4-style: App\ → src/)
│   ├── Admin/         # Admin controllers (auth, pages, settings, media, …)
│   ├── Content/       # Block editor and media manager
│   ├── Core/          # Router, Database, Config, Mailer, View, Seeder
│   ├── Models/        # Data models
│   ├── Public/        # Public site controller
│   ├── Seo/           # Meta tags, JSON-LD schemas, sitemap, robots, redirects
│   └── autoload.php   # Composer + App\ autoloader
├── templates/         # Plain-PHP views: admin/ and public/ layouts & pages
├── tests/             # php/ (11 test files) and js/ (1 test), run via run.php
├── specs/             # Spec-driven development docs (see below)
├── config/            # SQLite database (gitignored, created at runtime)
└── vendor/            # Composer dependencies
```

## Testing

```bash
php tests/run.php
```

The runner executes each plain-PHP test in `tests/php/` (hand-rolled asserts, no PHPUnit) and then runs `node tests/js/tabs.test.js` when Node.js is available, skipping it otherwise.

## Spec-Driven Workflow

This project is developed from specifications kept in `specs/`:

| Directory | Role |
|-----------|------|
| `specs/living/` | Merged source of truth for current behavior |
| `specs/changes/` | In-flight change proposals (`NNN-name/` with `proposal.md`, `design.md`, `specs/`, `tasks.md`) |
| `specs/archive/` | Completed changes, kept for audit |
| `specs/templates/` | Reusable spec templates |

Every feature starts as a change spec and is merged into `living/` on completion. Commits follow [Conventional Commits](https://www.conventionalcommits.org/).

## Contributing

1. Fork the repository and create a branch: `<type>/<short-description>` (e.g. `feat/page-scheduling`).
2. Add or update a spec under `specs/changes/` before writing code.
3. Keep changes small and framework-free — plain PHP 8.0+, vanilla JS/CSS.
4. Run `php tests/run.php` and make sure it passes.
5. Use [Conventional Commits](https://www.conventionalcommits.org/) (`feat`, `fix`, `docs`, `refactor`, `test`, `chore`, …).

## License

Not specified. No `LICENSE` file is included in this repository — all rights reserved by the author until one is added.
