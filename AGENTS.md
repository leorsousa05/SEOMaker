# SEOMaker AGENTS

This file is the operating guide for AI agents working in this repository.

## Project Summary

SEOMaker is a lightweight, framework-free PHP CMS for SEO-focused websites. The codebase uses plain PHP 8.0+, SQLite, vanilla JavaScript, and generated CSS assets. The public site, admin dashboard, SEO features, media tools, and tests are all maintained in one repository.

## Default Work Scope

Unless the user explicitly asks for something else, AI work in this repository must stay in the public-facing layer only.

### In scope by default
- Home page design and content
- Public-facing pages and layouts
- Public routes and controllers
- SEO output for public pages
- Public templates under `templates/public/`
- Public front-end assets under `public/assets/`
- User-visible interactions on the public site

### Out of scope by default
- Admin screens and flows
- `src/Admin/`
- `templates/admin/`
- Admin-specific assets and styles
- Database schema changes
- Authentication, authorization, and internal tools
- Any backend or infrastructure work that is not strictly required for public-facing design changes

### Scope rule
If a request implies admin work, backend work, schema changes, or any internal tooling, pause and ask for explicit confirmation before touching that area.

## Working Rules

1. Read the repository README and the relevant spec files before editing anything.
2. Treat `specs/` as the source of truth for planned work.
3. Create a spec in `specs/changes/NNN-name/` for every non-trivial change.
4. Prefer small, isolated changes that preserve existing behavior outside the requested scope.
5. Do not modify ignored files, secrets, or generated assets unless the task explicitly requires it.

## Spec-Driven Workflow

The project follows a spec-first process:

1. Draft the change in `specs/changes/`.
2. Implement only what the spec covers.
3. Run the repository tests.
4. Archive completed specs into `specs/archive/` when the change is finished.

Spec structure used in this repository:
- `proposal.md` for why the change exists
- `design.md` for how the change should work
- `tasks.md` for the implementation checklist
- `.spec.yaml` for metadata
- `specs/` for any delta or behavioral contract notes

## Repository Conventions

- Keep the stack plain and dependency-light.
- Follow the current folder boundaries:
  - `src/Core/` for core services
  - `src/Public/` for public controllers
  - `src/Admin/` for admin controllers
  - `src/Models/` for data models
  - `templates/public/` for public views
  - `templates/admin/` for admin views
  - `tests/` for PHP and JS tests
- Preserve the existing coding style and file organization when making changes.

## Testing Expectations

- Run `php tests/run.php` after making changes.
- If a change touches public JavaScript behavior, ensure the JS tests still pass through the existing runner.
- If a change affects rendering or layout, verify the relevant public pages in addition to the automated tests.

## Commit and Shipping Rules

- Use Conventional Commits.
- Keep branch names in `type/short-description` format.
- Ship only what is related to the approved task.
- Do not include unrelated admin/internal edits when the request is about public-facing work.

## Safety Rules

- Never commit secrets, credentials, or environment-specific data.
- Never rewrite unrelated user changes.
- Never assume admin changes are allowed just because a public task exists.
- When in doubt, keep the change public-facing and request explicit scope expansion.

## AI Operating Priority

1. Stay within the public-facing scope by default.
2. Follow the spec-driven workflow.
3. Preserve existing behavior unless the spec says otherwise.
4. Keep changes minimal, readable, and testable.
