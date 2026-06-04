<?php

declare(strict_types=1);

namespace App\Admin;

use App\Content\MediaManager;
use App\Core\Database;
use App\Core\View;

class MediaController
{
    public function index(): void
    {
        AuthController::requireAuth();
        
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $perPage = 24;
        
        $items = MediaManager::list($page, $perPage);
        $total = MediaManager::count();
        $totalPages = (int) ceil($total / $perPage);
        
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        
        View::layout('admin/layout');
        echo View::render('admin/media', [
            'items' => $items,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages,
            'flash' => $flash,
            'pageTitle' => 'Galeria de Mídia',
            'activeNav' => 'media',
        ]);
    }
    
    public function upload(): void
    {
        AuthController::requireAuth();
        
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método não permitido']);
            return;
        }
        
        if (!isset($_FILES['files'])) {
            echo json_encode(['success' => false, 'error' => 'Nenhum arquivo enviado']);
            return;
        }
        
        $files = $_FILES['files'];
        $results = [];
        
        // Handle single or multiple files
        if (is_array($files['name'])) {
            $count = count($files['name']);
            for ($i = 0; $i < $count; $i++) {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i],
                ];
                $results[] = MediaManager::upload($file);
            }
        } else {
            $results[] = MediaManager::upload($files);
        }
        
        echo json_encode(['success' => true, 'files' => $results]);
    }
    
    public function delete(array $params): void
    {
        AuthController::requireAuth();
        
        $id = isset($params['id']) ? (int) $params['id'] : 0;
        if ($id > 0 && MediaManager::delete($id)) {
            $_SESSION['flash'] = 'Imagem removida com sucesso!';
        }
        
        header('Location: /admin/media');
        exit;
    }
    
    public function json(): void
    {
        AuthController::requireAuth();
        
        header('Content-Type: application/json');
        
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $perPage = isset($_GET['perPage']) ? max(1, min(100, (int) $_GET['perPage'])) : 24;
        
        $items = MediaManager::list($page, $perPage);
        
        echo json_encode([
            'success' => true,
            'items' => $items,
            'page' => $page,
            'perPage' => $perPage,
        ]);
    }
}
