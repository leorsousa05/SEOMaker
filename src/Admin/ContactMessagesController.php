<?php

declare(strict_types=1);

namespace App\Admin;

use App\Core\View;
use App\Models\ContactMessage;

class ContactMessagesController
{
    public function index(): void
    {
        AuthController::requireAuth();
        
        $status = $_GET['status'] ?? 'all';
        
        if ($status === 'new') {
            $messages = \App\Core\Database::fetchAll('SELECT * FROM contact_messages WHERE status = ? ORDER BY created_at DESC', ['new']);
        } elseif ($status === 'replied') {
            $messages = \App\Core\Database::fetchAll('SELECT * FROM contact_messages WHERE status = ? ORDER BY created_at DESC', ['replied']);
        } elseif ($status === 'archived') {
            $messages = \App\Core\Database::fetchAll('SELECT * FROM contact_messages WHERE status = ? ORDER BY created_at DESC', ['archived']);
        } else {
            $messages = ContactMessage::findAll();
        }
        
        $counts = [
            'all' => (int) (\App\Core\Database::fetchOne('SELECT COUNT(*) as count FROM contact_messages')['count'] ?? 0),
            'new' => ContactMessage::countByStatus('new'),
            'replied' => ContactMessage::countByStatus('replied'),
            'archived' => ContactMessage::countByStatus('archived'),
        ];
        
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        
        View::layout('admin/layout');
        echo View::render('admin/messages', [
            'messages' => $messages,
            'counts' => $counts,
            'status' => $status,
            'flash' => $flash,
            'pageTitle' => 'Mensagens',
            'activeNav' => 'messages',
        ]);
    }
    
    public function reply(array $params): void
    {
        AuthController::requireAuth();
        
        $id = (int) ($params['id'] ?? 0);
        if ($id > 0) {
            ContactMessage::updateStatus($id, 'replied');
            $_SESSION['flash'] = 'Mensagem marcada como respondida.';
        }
        
        header('Location: /admin/messages');
        exit;
    }
    
    public function archive(array $params): void
    {
        AuthController::requireAuth();
        
        $id = (int) ($params['id'] ?? 0);
        if ($id > 0) {
            ContactMessage::updateStatus($id, 'archived');
            $_SESSION['flash'] = 'Mensagem arquivada.';
        }
        
        header('Location: /admin/messages');
        exit;
    }
    
    public function delete(array $params): void
    {
        AuthController::requireAuth();
        
        $id = (int) ($params['id'] ?? 0);
        if ($id > 0) {
            ContactMessage::delete($id);
            $_SESSION['flash'] = 'Mensagem removida.';
        }
        
        header('Location: /admin/messages');
        exit;
    }
}
