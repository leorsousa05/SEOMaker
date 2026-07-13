# Design: Project AGENTS Guidelines

## Architecture
This change introduces a repository-level instruction document that functions as the first-stop operating manual for future AI agents. It is a documentation-only addition with no runtime effect, so the design centers on clarity, discoverability, and safe defaults rather than application logic.

The document should sit at the repository root as `AGENTS.md` and reference the same spec-driven workflow already used elsewhere in the project. It should reinforce the current stack: plain PHP 8.0+, vanilla JavaScript, vanilla CSS/Tailwind-generated assets, SQLite persistence, and the existing router/view/model layout.

## Core Components
- `AGENTS.md`: the main instruction file for AI agents.
- `specs/changes/012-agents-project-guidelines/`: the tracked change record for this documentation update.
- `specs/changes/012-agents-project-guidelines/specs/agents/spec.md`: the delta describing the expected repository guidance behavior.

## Contracts
The document must explicitly define the following operating contracts for AI work:
- Default work scope is public-facing only.
- Admin/internal areas are out of scope unless explicitly requested.
- Public-facing scope includes the home page, public templates, public routes, SEO output, and user-visible front-end styling or behavior.
- Admin/internal scope includes `/admin`, `src/Admin`, `templates/admin`, admin-specific assets, and any backend/data-model changes.
- Every non-trivial change should be preceded by a spec in `specs/changes/`.
- Existing conventions, tests, and readme guidance must be read before editing.

## Guidance Sections to Include
- Repository purpose and stack.
- Working rules for AI agents.
- Scope restriction and escalation policy.
- Spec-driven workflow.
- Editing and testing conventions.
- File boundaries and do-not-touch areas.
- Commit, branch, and shipping expectations.

## Risk Assessment
- The main risk is over-broad guidance that becomes hard to follow. The document should stay concrete and repository-specific.
- Another risk is ambiguity about scope. The admin/public boundary must be stated clearly to avoid accidental work in `src/Admin` or `templates/admin`.
- The document should avoid prescribing implementation details that could become stale.

## Traceability
- Addressed: root-level project instructions.
- Addressed: English language requirement.
- Addressed: complete guidance for AI readers.
- Addressed: default restriction to public-facing work.
- Deferred: no runtime behavior changes, because this is documentation only.
