# Tasks: Manual Canonical URL per Page

## Setup
- [x] Create spec folder structure
- [x] Initialize `.spec.yaml`

## Implementation
- [ ] Add `canonical_url` column to `src/Core/Seeder.php`.
- [ ] Add `canonicalUrl` property to `src/Models/Page.php`.
- [ ] Update `Page::fromArray()`, `Page::toArray()` and `Page::validate()`.
- [ ] Update `src/Admin/PagesController.php` to persist `canonical_url`.
- [ ] Update `src/Seo/SeoManager.php` to use manual canonical when provided.
- [ ] Add input field to `templates/admin/pages_edit.php` SEO tab.

## Testing
- [ ] Create `tests/php/PageCanonicalTest.php`:
  - [ ] Default canonical is generated from slug.
  - [ ] Manual canonical overrides auto-generated value.
  - [ ] Empty manual canonical falls back to auto.
- [ ] Include test file in `tests/run.php`.
- [ ] Run `php tests/run.php`.

## Verification
- [ ] Set manual canonical on a page and check source.
- [ ] Leave field empty and confirm auto canonical.

## Documentation
- [ ] Update `specs/living/seo/spec.md` if applicable.

## Completion
- [ ] Archive change folder.
- [ ] Update `.spec.yaml` status to completed.
