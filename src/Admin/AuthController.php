<?php

declare(strict_types=1);

namespace App\Admin;

use App\Core\Database;
use App\Core\View;
use App\Models\User;

class AuthController
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $userData = Database::fetchOne('SELECT * FROM users WHERE username = ?', [$username]);
            
            if ($userData && password_verify($password, $userData['password_hash'])) {
                $_SESSION['admin_id'] = $userData['id'];
                $_SESSION['admin_user'] = $userData['username'];
                header('Location: /admin');
                exit;
            }
            
            $error = 'Usuário ou senha inválidos';
            echo View::render('admin/login', ['error' => $error]);
            return;
        }
        
        echo View::render('admin/login');
    }
    
    public function logout(): void
    {
        session_destroy();
        header('Location: /admin/login');
        exit;
    }
    
    public static function requireAuth(): void
    {
        if (empty($_SESSION['admin_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }
}
