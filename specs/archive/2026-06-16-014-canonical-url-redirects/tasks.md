# Tasks: Canonical WWW/Non-WWW and Trailing Slash Redirects

## Setup
- [x] Create spec folder structure
- [x] Initialize `.spec.yaml`

## Implementation
- [x] Seed `canonical_host` and `force_trailing_slash` settings in `src/Core/Seeder.php`.
- [x] Create `src/Core/CanonicalRedirect.php`.
- [x] Update `public/index.php` to check canonical redirect before routing.
- [x] Add settings fields to `src/Admin/SettingsController.php` and `templates/admin/settings.php`.

## Testing
- [x] Create `tests/php/CanonicalRedirectTest.php`:
  - [x] No redirect when settings are `auto`.
  - [x] Redirect non-www to www when configured.
  - [x] Redirect www to non-www when configured.
  - [x] Add trailing slash when configured.
  - [x] Remove trailing slash when configured.
  - [x] No redirect loop when already canonical.
- [x] Include test in `tests/run.php`.
- [x] Run `php tests/run.php`.

## Verification
- [x] Configure settings and test redirects via curl/browser.
- [x] Confirm 301 status code.

## Documentation
- [x] Update `specs/living/core/spec.md` if applicable.
- [x] Consider ADR for redirect precedence.

## Completion
- [x] Archive change folder.
- [x] Update `.spec.yaml` status to completed.
