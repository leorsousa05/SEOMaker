# Proposal: Public Product Rendering Refactor

## WHY
The current public product flow works, but it duplicates presentation and data-preparation logic across the homepage catalog cards and the product detail page. The controller also assembles product collections inline, which makes the public layer harder to reason about and harder to extend safely.

This refactor keeps the visible behavior the same while reducing repetition and clarifying the boundaries between controller data preparation, model-level computed values, and shared public view markup.

## Scope
- Refactor the public product rendering path only.
- Extract reusable product card markup into a public partial.
- Move repeated product view calculations into `App\Models\Product`.
- Centralize product collection loading in `App\Public\SiteController`.
- Keep public URLs, SEO output, and contact flow behavior unchanged.

## Constraints
- Public-facing scope only.
- No admin screens, admin controllers, or admin templates may change.
- No database schema changes are expected.
- The refactor must preserve the current appearance and output semantics of the public site.
- Any new helper or partial must remain framework-free and fit the current plain PHP view style.

## Scope Tree
```text
specs/changes/013-public-product-rendering-refactor/
├── .spec.yaml
├── proposal.md
├── design.md
├── tasks.md
└── specs/
    └── public/
        └── spec.md
```

## Expected Outcome
The public homepage and product detail page should consume the same product rendering primitives, with the controller supplying normalized product view data and the view layer using one shared card partial for catalog-style product displays.
