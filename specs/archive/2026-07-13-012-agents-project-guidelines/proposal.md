# Proposal: Project AGENTS Guidelines

## WHY
This change adds a root-level `AGENTS.md` file that codifies how AI agents should work in this repository. The goal is to make the project rules explicit, reduce ambiguity, and keep future work aligned with the existing spec-driven workflow.

## Scope
- Add a root `AGENTS.md` covering the whole repository.
- Document the project workflow, conventions, testing expectations, and safe operating rules for AI agents.
- Encode the default scope restriction: unless the user explicitly asks otherwise, AI work should be limited to the public-facing home page and user-facing pages.

## Constraints
- The file must be written in English.
- The guidance must be practical and detailed enough for agent use.
- The document must clearly separate public-facing work from admin/internal work.
- No implementation code changes are required beyond the documentation file and its spec record.
