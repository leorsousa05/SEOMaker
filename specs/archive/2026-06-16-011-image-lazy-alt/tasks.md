# Tasks: Lazy Loading and Required Alt Text for Images

## Setup
- [x] Create spec folder structure
- [x] Initialize `.spec.yaml`

## Implementation
- [ ] Add `loading` strategy to image/gallery rendering in `BlockEditor.php`.
- [ ] Add `BlockEditor::validateBlocks()` and `requiresAltText()`.
- [ ] Update `PagesController::save()` to validate blocks and show errors.
- [ ] Update `block-editor.js` to require alt text client-side.
- [ ] Update `pages_edit.php` to display block validation errors.

## Testing
- [ ] Create `tests/php/ImageLazyAltTest.php`:
  - [ ] First image renders `loading="eager"`.
  - [ ] Subsequent images render `loading="lazy"`.
  - [ ] Missing alt text triggers validation error.
- [ ] Include test in `tests/run.php`.
- [ ] Run `php tests/run.php`.

## Verification
- [ ] Add image blocks without alt and confirm save is rejected.
- [ ] Inspect rendered HTML for correct `loading` attributes.

## Documentation
- [ ] Update `specs/living/content/block-editor/spec.md` if applicable.

## Completion
- [ ] Archive change folder.
- [ ] Update `.spec.yaml` status to completed.
