<?php

declare(strict_types=1);

namespace App\Admin;

use App\Core\Database;
use App\Core\View;
use App\Models\Page;

class PagesController
{
    public function index(): void
    {
        AuthController::requireAuth();
        
        $pages = Database::fetchAll('SELECT * FROM pages ORDER BY id DESC');
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        
        View::layout('admin/layout');
        echo View::render('admin/pages', ['pages' => $pages, 'flash' => $flash]);
    }
    
    public function edit(array $params): void
    {
        AuthController::requireAuth();
        
        $id = isset($params['id']) ? (int) $params['id'] : 0;
        $page = $id > 0 ? Database::fetchOne('SELECT * FROM pages WHERE id = ?', [$id]) : null;
        
        $schemaTypes = ['WebPage', 'WebSite', 'Organization', 'ContactPage', 'AboutPage', 'Service'];
        
        View::layout('admin/layout');
        echo View::render('admin/pages_edit', [
            'page' => $page,
            'schemaTypes' => $schemaTypes,
        ]);
    }
    
    public function save(): void
    {
        AuthController::requireAuth();
        
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        
        $data = [
            'slug' => $_POST['slug'] ?? '',
            'title' => $_POST['title'] ?? '',
            'meta_title' => $_POST['meta_title'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'content_html' => $_POST['content_html'] ?? '',
            'schema_type' => $_POST['schema_type'] ?? 'WebPage',
            'schema_data' => $_POST['schema_data'] ?? '{}',
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        if ($id > 0) {
            Database::update('pages', $data, 'id = ?', [$id]);
            $_SESSION['flash'] = 'Página atualizada com sucesso!';
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            Database::insert('pages', $data);
            $_SESSION['flash'] = 'Página criada com sucesso!';
        }
        
        header('Location: /admin/pages');
        exit;
    }
    
    public function delete(array $params): void
    {
        AuthController::requireAuth();
        
        $id = isset($params['id']) ? (int) $params['id'] : 0;
        if ($id > 0) {
            Database::delete('pages', 'id = ?', [$id]);
            $_SESSION['flash'] = 'Página removida com sucesso!';
        }
        
        header('Location: /admin/pages');
        exit;
    }
}
