<?php

declare(strict_types=1);

namespace App\Public;

use App\Core\Config;
use App\Core\Database;
use App\Core\Mailer;
use App\Core\View;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Seo\RobotsBuilder;
use App\Seo\SitemapGenerator;

class SiteController
{
    public function home(): void
    {
        $pageData = Database::fetchOne("SELECT * FROM pages WHERE slug = '' LIMIT 1");
        $page = $pageData ? Page::fromArray($pageData) : new Page();
        
        $features = $this->getLandingFeatures();
        
        View::layout('public/layout');
        echo View::render('public/home', [
            'page' => $page,
            'features' => $features,
            'isHome' => true,
        ]);
    }
    
    public function page(array $params): void
    {
        $slug = $params['slug'] ?? '';
        $pageData = Database::fetchOne('SELECT * FROM pages WHERE slug = ? AND is_active = 1', [$slug]);
        
        if (!$pageData) {
            http_response_code(404);
            View::layout('public/layout');
            echo \App\Core\View::render('public/404');
            return;
        }
        
        $page = Page::fromArray($pageData);
        
        View::layout('public/layout');
        echo View::render('public/page', [
            'page' => $page,
            'isHome' => false,
        ]);
    }
    
    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        echo SitemapGenerator::cachedGenerate();
    }

    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        echo RobotsBuilder::cachedGenerate();
    }
    
    public function contact(): void
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'message' => $_POST['message'] ?? '',
        ];
        
        $errors = ContactMessage::validate($data);
        if (!empty($errors)) {
            $_SESSION['contact_errors'] = $errors;
            $_SESSION['contact_data'] = $data;
            header('Location: /?contact=error');
            exit;
        }
        
        if (ContactMessage::isRateLimited()) {
            $_SESSION['contact_errors'] = ['rate' => 'Aguarde um minuto antes de enviar outra mensagem.'];
            $_SESSION['contact_data'] = $data;
            header('Location: /?contact=error');
            exit;
        }
        
        ContactMessage::create($data);
        ContactMessage::markSent();
        
        $siteTitle = Config::get('site_title', 'Site');
        $to = Config::get('contact_email', 'admin@example.com');
        $subject = 'Nova mensagem de ' . $data['name'] . ' — ' . $siteTitle;
        
        $body = "<p><strong>Nome:</strong> " . htmlspecialchars($data['name']) . "</p>\n";
        $body .= "<p><strong>Email:</strong> " . htmlspecialchars($data['email']) . "</p>\n";
        if (!empty($data['phone'])) {
            $body .= "<p><strong>Telefone:</strong> " . htmlspecialchars($data['phone']) . "</p>\n";
        }
        $body .= "<p><strong>Mensagem:</strong></p>\n<p>" . nl2br(htmlspecialchars($data['message'])) . "</p>";
        
        Mailer::send($to, $subject, $body);
        
        $_SESSION['contact_success'] = 'Mensagem enviada com sucesso!';
        header('Location: /?contact=sent');
        exit;
    }
    
    /**
     * @return array<int, array<string, string>>
     */
    private function getLandingFeatures(): array
    {
        $phpIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>';
        $databaseIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>';
        $adminIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>';
        $seoIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="11"/><line x1="11" y1="11" x2="14" y2="11"/></svg>';
        $templateIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>';
        $testIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';

        return [
            ['icon' => $phpIcon, 'title' => 'Código PHP 8 puro', 'desc' => 'Sem Laravel, sem Symfony. Autoloading PSR-4, strict_types e arquitetura modular.'],
            ['icon' => $databaseIcon, 'title' => 'Banco SQLite embutido', 'desc' => 'Sem configurar PostgreSQL ou MySQL. O banco fica em um único arquivo.'],
            ['icon' => $adminIcon, 'title' => 'Admin funcional de verdade', 'desc' => 'CRUD de páginas, upload de mídia, redirecionamentos 301/302, mensagens e configurações.'],
            ['icon' => $seoIcon, 'title' => 'SEO técnico automático', 'desc' => 'Meta tags, Open Graph, Twitter Cards, Schema.org, sitemap.xml e robots.txt dinâmicos.'],
            ['icon' => $templateIcon, 'title' => 'Templates PHP simples', 'desc' => 'Sistema de View com layouts e partials. Fácil de tematizar e estender.'],
            ['icon' => $testIcon, 'title' => 'Testes incluídos', 'desc' => 'Testes PHP e JS cobrindo SEO, schemas, páginas, redirects e muito mais.'],
        ];
    }
}
