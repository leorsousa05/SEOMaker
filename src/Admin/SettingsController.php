<?php

declare(strict_types=1);

namespace App\Admin;

use App\Core\Config;
use App\Core\View;

class SettingsController
{
    public function index(): void
    {
        AuthController::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $allowed = [
                'site_title', 'site_description', 'site_url', 'site_logo',
                'contact_email', 'contact_phone', 'analytics_id',
                'og_image', 'mail_from', 'mail_from_name',
            ];
            
            foreach ($allowed as $key) {
                if (isset($_POST[$key])) {
                    Config::set($key, $_POST[$key]);
                }
            }
            
            $_SESSION['flash'] = 'Configurações salvas com sucesso!';
            header('Location: /admin/settings');
            exit;
        }
        
        $settings = Config::all();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        
        View::layout('admin/layout');
        echo View::render('admin/settings', [
            'settings' => $settings,
            'flash' => $flash,
        ]);
    }
}
