# Tasks: Sitemap.xml and Robots.txt Caching

## Setup
- [x] Create spec folder structure
- [x] Initialize `.spec.yaml`

## Implementation
- [x] Create `config/cache/` directory in `src/Core/Seeder.php`.
- [x] Implement `src/Core/FileCache.php`.
- [x] Add `cachedGenerate()` to `src/Seo/SitemapGenerator.php`.
- [x] Add `cachedGenerate()` to `src/Seo/RobotsBuilder.php`.
- [x] Update `src/Public/SiteController.php` to use cached methods.
- [x] Invalidate cache in `PagesController::save/delete`.
- [x] Invalidate cache in `SettingsController::index` POST.
- [x] Invalidate cache in `RedirectsController` mutations.

## Testing
- [x] Create `tests/php/SitemapRobotsCacheTest.php`:
  - [x] Cache file is generated on first request.
  - [x] Cached content is returned on subsequent requests.
  - [x] Cache is invalidated on page save.
- [x] Include test in `tests/run.php`.
- [x] Run `php tests/run.php`.

## Verification
- [x] Request `/sitemap.xml` and verify file in `config/cache/`.
- [x] Edit a page and confirm cache file is removed/regenerated.

## Documentation
- [x] Update `specs/living/seo/spec.md` if applicable.
- [x] Add ADR if cache directory location is a significant decision.

## Completion
- [x] Archive change folder.
- [x] Update `.spec.yaml` status to completed.
