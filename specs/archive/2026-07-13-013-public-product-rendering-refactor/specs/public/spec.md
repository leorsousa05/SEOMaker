# Public Product Rendering Delta

## MODIFIED
- The homepage catalog cards and the product detail related-product cards now share the same public rendering contract.
- Public product display logic moves away from inline template calculations and into `App\Models\Product` helper methods.
- `App\Public\SiteController` becomes responsible for loading and normalizing public product collections before rendering.

## ADDED
- A reusable `templates/public/_product_card.php` partial for product cards.
- Product display helpers for price, discount, URL, and CTA selection.

## REMOVED
- Duplicate product card markup and duplicated display calculations inside public page templates.
