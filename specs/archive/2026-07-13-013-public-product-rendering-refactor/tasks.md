# Tasks: Public Product Rendering Refactor

- [x] Add computed display helpers to `App\Models\Product`.
- [x] Move featured-product and related-product loading into private `SiteController` methods.
- [x] Create `templates/public/_product_card.php` as the shared public product card partial.
- [x] Update `templates/public/home.php` to use the shared card partial.
- [x] Update `templates/public/product.php` to use the shared card partial for related products.
- [x] Update or extend `tests/php/ProductTest.php` for the new helper methods.
- [x] Run `php tests/run.php` and verify the public-facing refactor does not alter current behavior.
