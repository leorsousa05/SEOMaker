<?php

declare(strict_types=1);

namespace App\Public;

use App\Core\Config;
use App\Core\Database;
use App\Core\Mailer;
use App\Core\View;
use App\Models\Page;
use App\Seo\SeoManager;
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
        ]);
    }
    
    public function page(array $params): void
    {
        $slug = $params['slug'] ?? '';
        $pageData = Database::fetchOne('SELECT * FROM pages WHERE slug = ? AND is_active = 1', [$slug]);
        
        if (!$pageData) {
            http_response_code(404);
            View::layout('public/layout');
            echo View::render('public/404');
            return;
        }
        
        $page = Page::fromArray($pageData);
        
        View::layout('public/layout');
        echo View::render('public/page', ['page' => $page]);
    }
    
    public function sitemap(): void
    {
        header('Content-Type: application/xml');
        echo SitemapGenerator::generate();
    }
    
    public function robots(): void
    {
        header('Content-Type: text/plain');
        $siteUrl = rtrim(Config::get('site_url', 'https://example.com'), '/');
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Sitemap: {$siteUrl}/sitemap.xml\n";
    }
    
    public function contact(): void
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';
        
        $errors = [];
        if (empty($name) || strlen($name) < 2) {
            $errors[] = 'Nome é obrigatório';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        }
        if (empty($message) || strlen($message) < 10) {
            $errors[] = 'Mensagem muito curta';
        }
        
        if (!empty($errors)) {
            $_SESSION['contact_errors'] = $errors;
            $_SESSION['contact_data'] = compact('name', 'email', 'message');
            header('Location: /');
            exit;
        }
        
        $to = Config::get('contact_email', 'admin@example.com');
        $subject = 'Novo contato do site: ' . $name;
        $body = "<p><strong>Nome:</strong> {$name}</p>\n";
        $body .= "<p><strong>Email:</strong> {$email}</p>\n";
        $body .= "<p><strong>Mensagem:</strong></p>\n<p>" . nl2br(htmlspecialchars($message)) . "</p>";
        
        Mailer::send($to, $subject, $body);
        
        $_SESSION['contact_success'] = 'Mensagem enviada com sucesso!';
        header('Location: /');
        exit;
    }
    
    /**
     * @return array<int, array<string, string>>
     */
    private function getLandingFeatures(): array
    {
        return [
            ['icon' => '🚀', 'title' => 'Rápido', 'desc' => 'Performance otimizada para SEO.'],
            ['icon' => '🎯', 'title' => 'Focado', 'desc' => 'Estrutura semântica perfeita.'],
            ['icon' => '📊', 'title' => 'Completo', 'desc' => 'Schema.org, sitemap e meta tags.'],
        ];
    }
}
