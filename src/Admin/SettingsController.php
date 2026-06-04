<?php

declare(strict_types=1);

namespace App\Admin;

use App\Core\Config;
use App\Core\View;

class SettingsController
{
    /** @var array<string, array{label: string, keys: array<int, string>}> */
    private array $groups = [
        'geral' => [
            'label' => 'Geral',
            'keys' => ['site_title', 'site_description', 'site_url', 'site_logo', 'favicon'],
        ],
        'seo' => [
            'label' => 'SEO',
            'keys' => ['meta_default_title', 'meta_default_description', 'og_image', 'robots_txt_custom'],
        ],
        'contato' => [
            'label' => 'Contato',
            'keys' => ['contact_email', 'contact_phone', 'contact_address', 'contact_whatsapp'],
        ],
        'email' => [
            'label' => 'Email',
            'keys' => ['mail_from', 'mail_from_name', 'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass'],
        ],
        'social' => [
            'label' => 'Redes Sociais',
            'keys' => ['facebook_url', 'instagram_url', 'twitter_url', 'linkedin_url', 'youtube_url'],
        ],
    ];

    /** @var array<string, string> */
    private array $labels = [
        'site_title' => 'Título do Site',
        'site_description' => 'Descrição do Site',
        'site_url' => 'URL do Site',
        'site_logo' => 'Logo (caminho)',
        'favicon' => 'Favicon (caminho)',
        'meta_default_title' => 'Meta Título Padrão',
        'meta_default_description' => 'Meta Descrição Padrão',
        'og_image' => 'Imagem Open Graph',
        'robots_txt_custom' => 'Conteúdo Customizado do Robots.txt',
        'contact_email' => 'Email de Contato',
        'contact_phone' => 'Telefone',
        'contact_address' => 'Endereço',
        'contact_whatsapp' => 'WhatsApp',
        'mail_from' => 'Email Remetente',
        'mail_from_name' => 'Nome do Remetente',
        'smtp_host' => 'SMTP Host',
        'smtp_port' => 'SMTP Porta',
        'smtp_user' => 'SMTP Usuário',
        'smtp_pass' => 'SMTP Senha',
        'facebook_url' => 'Facebook URL',
        'instagram_url' => 'Instagram URL',
        'twitter_url' => 'Twitter URL',
        'linkedin_url' => 'LinkedIn URL',
        'youtube_url' => 'YouTube URL',
    ];

    public function index(): void
    {
        AuthController::requireAuth();
        
        $allowed = [];
        foreach ($this->groups as $g) {
            $allowed = array_merge($allowed, $g['keys']);
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($allowed as $key) {
                if (isset($_POST[$key])) {
                    Config::set($key, $_POST[$key]);
                }
            }
            
            $activeTab = $_POST['active_tab'] ?? 'geral';
            $_SESSION['flash'] = 'Configurações salvas com sucesso!';
            header('Location: /admin/settings#' . $activeTab);
            exit;
        }
        
        $settings = Config::all();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        
        $activeTab = 'geral';
        if (isset($_SERVER['HTTP_REFERER']) && str_contains($_SERVER['HTTP_REFERER'], '#')) {
            $activeTab = substr($_SERVER['HTTP_REFERER'], strrpos($_SERVER['HTTP_REFERER'], '#') + 1);
        }
        
        View::layout('admin/layout');
        echo View::render('admin/settings', [
            'settings' => $settings,
            'flash' => $flash,
            'groups' => $this->groups,
            'activeTab' => $activeTab,
            'labels' => $this->labels,
            'pageTitle' => 'Configurações',
            'activeNav' => 'settings',
        ]);
    }
}
