# Tasks: Page-Level Robots Control

## Setup
- [x] Create spec folder structure
- [x] Initialize `.spec.yaml`

## Implementation
- [ ] Add `meta_robots` column to `src/Core/Seeder.php` via `ALTER TABLE`.
- [ ] Add `metaRobots` property and `defaultMetaRobots()` to `src/Models/Page.php`.
- [ ] Update `Page::fromArray()`, `Page::toArray()` and `Page::validate()`.
- [ ] Update `src/Admin/PagesController.php` to sanitize and persist `meta_robots`.
- [ ] Add robots `<meta>` rendering to `src/Seo/SeoManager.php`.
- [ ] Add UI field to `templates/admin/pages_edit.php` in the SEO tab.

## Testing
- [ ] Create `tests/php/PageRobotsTest.php`:
  - [ ] Default robots is `index, follow`.
  - [ ] `noindex, nofollow` renders correctly.
  - [ ] Invalid tokens are stripped.
- [ ] Include test file in `tests/run.php`.
- [ ] Run `php tests/run.php` and ensure all tests pass.

## Verification
- [ ] Edit a page and set robots to `noindex, nofollow`.
- [ ] View public page source and confirm meta tag.
- [ ] Verify existing pages still render `index, follow`.

## Documentation
- [ ] Update `specs/living/seo/spec.md` if applicable.
- [ ] No ADR required.

## Completion
- [ ] Archive change folder when implemented.
- [ ] Update `.spec.yaml` status to completed.
