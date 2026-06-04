<?php

declare(strict_types=1);

namespace App\Admin;

use App\Core\Database;
use App\Core\View;

class DashboardController
{
    public function index(): void
    {
        AuthController::requireAuth();
        
        $stats = [
            'total_pages' => Database::fetchOne('SELECT COUNT(*) as count FROM pages')['count'] ?? 0,
            'active_pages' => Database::fetchOne('SELECT COUNT(*) as count FROM pages WHERE is_active = 1')['count'] ?? 0,
            'last_update' => Database::fetchOne('SELECT MAX(updated_at) as last FROM pages')['last'] ?? '-',
        ];
        
        View::layout('admin/layout');
        echo View::render('admin/dashboard', ['stats' => $stats]);
    }
}
