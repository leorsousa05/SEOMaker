# Tasks: Visual Breadcrumbs + Schema

## Setup
- [x] Create spec folder structure
- [x] Initialize `.spec.yaml`

## Implementation
- [ ] Add `SeoManager::breadcrumbItems(Page $page): array`.
- [ ] Refactor `SeoManager::breadcrumbSchema()` to use the new helper.
- [ ] Create `templates/public/partials/_breadcrumbs.php`.
- [ ] Include partial in `templates/public/layout.php`.
- [ ] Add breadcrumb styles to `public/assets/style.css`.

## Testing
- [ ] Create `tests/php/BreadcrumbTemplateTest.php`:
  - [ ] Homepage does not render breadcrumbs.
  - [ ] Regular page renders Home > Page Title.
  - [ ] JSON-LD schema matches visual breadcrumb.
- [ ] Include test in `tests/run.php`.
- [ ] Run `php tests/run.php`.

## Verification
- [ ] Visit public page and confirm breadcrumb navigation.
- [ ] Validate JSON-LD schema still present.

## Documentation
- [ ] Update `specs/living/public/spec.md` if applicable.

## Completion
- [ ] Archive change folder.
- [ ] Update `.spec.yaml` status to completed.
