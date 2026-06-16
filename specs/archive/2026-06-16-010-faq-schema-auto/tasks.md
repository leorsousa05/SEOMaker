# Tasks: Auto FAQPage Schema from FAQ Block

## Setup
- [x] Create spec folder structure
- [x] Initialize `.spec.yaml`

## Implementation
- [ ] Add `SeoManager::faqSchema(Page $page): ?array`.
- [ ] Add private `SeoManager::extractFaqItems(array $blocks): array`.
- [ ] Update `SeoManager::schemaJsonLd()` to include FAQPage when present.
- [ ] Update `templates/public/layout.php` to ensure FAQ schema is emitted.

## Testing
- [ ] Create `tests/php/FaqSchemaTest.php`:
  - [ ] Page without FAQ block returns no FAQPage schema.
  - [ ] Page with FAQ block generates valid FAQPage JSON-LD.
  - [ ] Empty FAQ items are filtered out.
- [ ] Include test in `tests/run.php`.
- [ ] Run `php tests/run.php`.

## Verification
- [ ] Add FAQ block to a page and inspect source for JSON-LD.
- [ ] Validate schema no Google Rich Results Test (manual).

## Documentation
- [ ] Update `specs/living/seo/spec.md` if applicable.

## Completion
- [ ] Archive change folder.
- [ ] Update `.spec.yaml` status to completed.
