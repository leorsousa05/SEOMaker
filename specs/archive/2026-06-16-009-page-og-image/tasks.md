# Tasks: Per-Page Open Graph/Twitter Image

## Setup
- [x] Create spec folder structure
- [x] Initialize `.spec.yaml`

## Implementation
- [ ] Add `og_image_id` column to `src/Core/Seeder.php`.
- [ ] Add `ogImageId` property and `ogImageUrl()` method to `src/Models/Page.php`.
- [ ] Update `Page::fromArray()`, `Page::toArray()` and `Page::validate()`.
- [ ] Update `src/Admin/PagesController.php` to persist `og_image_id`.
- [ ] Update `src/Seo/SeoManager.php` to use page-level image first.
- [ ] Add media picker UI to `templates/admin/pages_edit.php` SEO tab.

## Testing
- [ ] Create `tests/php/PageOgImageTest.php`:
  - [ ] Fallback to global og_image when page has none.
  - [ ] Page-level image overrides global.
  - [ ] Invalid media ID falls back to global.
- [ ] Include test in `tests/run.php`.
- [ ] Run `php tests/run.php`.

## Verification
- [ ] Select image for a page and check `og:image` in source.
- [ ] Remove selection and confirm global fallback.

## Documentation
- [ ] Update `specs/living/seo/spec.md` if applicable.

## Completion
- [ ] Archive change folder.
- [ ] Update `.spec.yaml` status to completed.
