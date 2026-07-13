<?php

declare(strict_types=1);

namespace App\Admin;

use App\Core\Database;
use App\Core\View;
use App\Models\Product;

class ProductsController
{
    public function index(): void
    {
        AuthController::requireAuth();

        $products = Database::fetchAll('SELECT p.*, m.path AS image_path FROM products p LEFT JOIN media m ON p.image_id = m.id ORDER BY p.id DESC');
        $flash    = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        View::layout('admin/layout');
        echo View::render('admin/products', [
            'products'  => $products,
            'flash'     => $flash,
            'pageTitle' => 'Produtos',
            'activeNav' => 'products',
        ]);
    }

    public function edit(array $params): void
    {
        AuthController::requireAuth();

        $id     = isset($params['id']) ? (int) $params['id'] : 0;
        $isEdit = $id > 0;
        $product = $id > 0 ? Database::fetchOne('SELECT * FROM products WHERE id = ?', [$id]) : null;

        // Fetch image path if exists
        $imagePath = null;
        if ($product && !empty($product['image_id'])) {
            $media = Database::fetchOne('SELECT path FROM media WHERE id = ?', [$product['image_id']]);
            if ($media) {
                $imagePath = $media['path'];
            }
        }

        // Decode gallery
        $galleryItems = [];
        if ($product && !empty($product['gallery_ids'])) {
            $ids = json_decode($product['gallery_ids'], true);
            if (is_array($ids) && !empty($ids)) {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $galleryItems = Database::fetchAll("SELECT id, path FROM media WHERE id IN ($placeholders)", $ids);
            }
        }

        View::layout('admin/layout');
        echo View::render('admin/products_edit', [
            'product'     => $product,
            'imagePath'   => $imagePath,
            'galleryItems' => $galleryItems,
            'pageTitle'   => $isEdit ? 'Editar Produto' : 'Novo Produto',
            'activeNav'   => 'products',
            'headerActions' => '<a href="/admin/products" class="btn btn-ghost">← Voltar</a>',
        ]);
    }

    public function save(): void
    {
        AuthController::requireAuth();

        $id   = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $name = $_POST['name'] ?? '';
        $slug = $_POST['slug'] ?? '';

        if ($slug === '' && $name !== '') {
            $slug = Product::generateSlug($name);
        }

        $validationData = [
            'name'       => $name,
            'price'      => $_POST['price'] ?? '',
            'promo_price' => $_POST['promo_price'] ?? '',
            'slug'       => $slug,
        ];

        $errors = Product::validate($validationData, $id > 0 ? $id : null);
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data']   = $_POST;
            $redirect = $id > 0 ? '/admin/products/edit/' . $id : '/admin/products/edit';
            header('Location: ' . $redirect);
            exit;
        }

        $promoPrice = $_POST['promo_price'] ?? '';
        $imageId    = $_POST['image_id'] ?? '';

        $data = [
            'name'              => $name,
            'slug'              => $slug,
            'short_description' => $_POST['short_description'] ?? '',
            'description'       => $_POST['description'] ?? '',
            'price'             => (float) ($_POST['price'] ?? 0),
            'promo_price'       => ($promoPrice !== '' && $promoPrice !== null) ? (float) $promoPrice : null,
            'image_id'          => ($imageId !== '' && $imageId !== null) ? (int) $imageId : null,
            'gallery_ids'       => $_POST['gallery_ids'] ?? '[]',
            'category'          => $_POST['category'] ?? '',
            'tags'              => $_POST['tags'] ?? '',
            'sku'               => $_POST['sku'] ?? '',
            'stock'             => (int) ($_POST['stock'] ?? 0),
            'external_link'     => $_POST['external_link'] ?? '',
            'featured'          => isset($_POST['featured']) ? 1 : 0,
            'is_active'         => isset($_POST['is_active']) ? 1 : 0,
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        if ($id > 0) {
            Database::update('products', $data, 'id = ?', [$id]);
            $_SESSION['flash'] = 'Produto atualizado com sucesso!';
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            Database::insert('products', $data);
            $_SESSION['flash'] = 'Produto criado com sucesso!';
        }

        header('Location: /admin/products');
        exit;
    }

    public function delete(array $params): void
    {
        AuthController::requireAuth();

        $id = isset($params['id']) ? (int) $params['id'] : 0;
        if ($id > 0) {
            Database::delete('products', 'id = ?', [$id]);
            $_SESSION['flash'] = 'Produto removido com sucesso!';
        }

        header('Location: /admin/products');
        exit;
    }
}
