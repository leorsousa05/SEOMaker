# Design: SEO Template PHP

## Architecture

```
seo-template/
├── public/                 ← entry point, assets
│   ├── index.php           ← front controller
│   ├── assets/
│   └── .htaccess           ← rewrite to index.php
├── src/
│   ├── Core/               ← router, config, db, mail
│   ├── Seo/                ← schema gen, sitemap gen, meta manager
│   ├── Admin/              ← auth, dashboard controllers
│   └── Models/             ← entities, repositories
├── config/
│   └── database.sqlite
├── templates/              ← views (public + admin)
│   ├── public/
│   └── admin/
└── specs/
```

## Core Components

### Router (`Core\Router`)
- Map routes → Controller@action
- Support GET/POST
- Middleware support (auth)

### Config (`Core\Config`)
- Load from DB (settings table)
- Cache in memory per request
- Keys: site_title, site_description, analytics_id, contact_email, etc.

### SEO Engine (`Seo\SeoManager`)
- `generateMetaTags(): string` — title, description, canonical, og, twitter
- `generateSchema(string $type, array $data): array` — return JSON-LD array
- `generateSitemap(): string` — XML sitemap from pages table

### Email (`Core\Mailer`)
- SMTP or PHP mail()
- Template-based

### Admin (`Admin\DashboardController`)
- GET /admin/login
- POST /admin/login
- GET /admin/dashboard
- GET /admin/settings
- POST /admin/settings
- GET /admin/pages
- GET /admin/pages/edit/{id}
- POST /admin/pages/save

## Data Model

### settings
| key | value | updated_at |

### pages
| id | slug | title | meta_title | meta_description | content_html | schema_type | schema_data | is_active | created_at | updated_at |

### users (admin)
| id | username | password_hash | created_at |

## Contracts

```php
interface RouterInterface {
    public function get(string $path, callable|string $handler, ?string $name = null): void;
    public function post(string $path, callable|string $handler, ?string $name = null): void;
    public function dispatch(): void;
}

interface SeoManagerInterface {
    public function metaTags(Page $page): string;
    public function schemaJsonLd(Page $page): string;
    public function sitemapXml(): string;
}

interface MailerInterface {
    public function send(string $to, string $subject, string $body): bool;
}
```

## Routes

### Public
- GET / → Landing page
- GET /page/{slug} → Generic page
- GET /sitemap.xml → Sitemap
- POST /contact → Send email

### Admin
- GET|POST /admin/login
- GET /admin/logout
- GET /admin → Dashboard
- GET|POST /admin/settings
- GET|POST /admin/pages
- GET|POST /admin/pages/edit/{id}
