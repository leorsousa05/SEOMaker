<?php

declare(strict_types=1);

namespace App\Admin;

use App\Core\Database;
use App\Core\View;
use App\Models\Redirect;

class RedirectsController
{
    public function index(): void
    {
        AuthController::requireAuth();
        
        $redirects = Redirect::findAll();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        
        View::layout('admin/layout');
        echo View::render('admin/redirects', [
            'redirects' => $redirects,
            'flash' => $flash,
            'pageTitle' => 'Redirects',
            'activeNav' => 'redirects',
        ]);
    }
    
    public function edit(array $params = []): void
    {
        AuthController::requireAuth();
        
        $id = isset($params['id']) ? (int) $params['id'] : 0;
        $redirect = $id > 0 ? Database::fetchOne('SELECT * FROM redirects WHERE id = ?', [$id]) : null;
        
        $errors = $_SESSION['form_errors'] ?? [];
        $formData = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_errors'], $_SESSION['form_data']);
        
        if (!empty($formData) && $redirect !== null) {
            $redirect = array_merge($redirect, $formData);
        } elseif (!empty($formData)) {
            $redirect = $formData;
        }
        
        View::layout('admin/layout');
        echo View::render('admin/redirects_edit', [
            'redirect' => $redirect,
            'errors' => $errors,
            'pageTitle' => $id > 0 ? 'Editar Redirect' : 'Novo Redirect',
            'activeNav' => 'redirects',
            'headerActions' => '<a href="/admin/redirects" class="btn btn-ghost">← Voltar</a>',
        ]);
    }
    
    public function save(): void
    {
        AuthController::requireAuth();
        
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $from = trim($_POST['from_path'] ?? '');
        $to = trim($_POST['to_path'] ?? '');
        
        $data = [
            'from_path' => $from,
            'to_path' => $to,
            'type' => $_POST['type'] ?? '301',
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
        
        $errors = Redirect::validate($data, $id > 0 ? $id : null);
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            $redirect = $id > 0 ? '/admin/redirects/edit/' . $id : '/admin/redirects/edit';
            header('Location: ' . $redirect);
            exit;
        }
        
        if ($id > 0) {
            Redirect::update($id, $data);
            $_SESSION['flash'] = 'Redirect atualizado com sucesso!';
        } else {
            Redirect::create($data);
            $_SESSION['flash'] = 'Redirect criado com sucesso!';
        }
        
        header('Location: /admin/redirects');
        exit;
    }
    
    public function delete(array $params): void
    {
        AuthController::requireAuth();
        
        $id = isset($params['id']) ? (int) $params['id'] : 0;
        if ($id > 0) {
            Redirect::delete($id);
            $_SESSION['flash'] = 'Redirect removido com sucesso!';
        }
        
        header('Location: /admin/redirects');
        exit;
    }
}
