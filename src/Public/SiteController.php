<?php

declare(strict_types=1);

namespace App\Public;

use App\Core\Config;
use App\Core\Database;
use App\Core\Mailer;
use App\Core\View;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\Product;
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
        echo View::render('public/page', ['page' => $page]);
    }
    
    public function product(array $params): void
    {
        $slug = $params['slug'] ?? '';
        $productData = Database::fetchOne(
            'SELECT p.*, m.path AS image_path FROM products p LEFT JOIN media m ON p.image_id = m.id WHERE p.slug = ? AND p.is_active = 1',
            [$slug]
        );

        if (!$productData) {
            http_response_code(404);
            View::layout('public/layout');
            echo View::render('public/404');
            return;
        }

        $product = Product::fromArray($productData);

        // Fetch gallery images
        $galleryImages = [];
        if (!empty($productData['gallery_ids'])) {
            $ids = json_decode($productData['gallery_ids'], true);
            if (is_array($ids) && !empty($ids)) {
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $galleryImages = Database::fetchAll("SELECT id, path FROM media WHERE id IN ($placeholders)", $ids);
            }
        }

        // Related products (same category, excluding current)
        $related = [];
        if (!empty($product->category)) {
            $related = Database::fetchAll(
                'SELECT p.*, m.path AS image_path FROM products p LEFT JOIN media m ON p.image_id = m.id WHERE p.category = ? AND p.slug != ? AND p.is_active = 1 ORDER BY p.id DESC LIMIT 4',
                [$product->category, $slug]
            );
        }

        View::layout('public/layout');
        echo View::render('public/product', [
            'product'       => $product,
            'imagePath'     => $productData['image_path'] ?? null,
            'galleryImages' => $galleryImages,
            'related'       => $related,
        ]);
    }

    public function redirectToSlug(array $params): void
    {
        $slug = $params['slug'] ?? '';
        header('Location: /' . $slug, true, 301);
        exit;
    }
    
    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');
        echo SitemapGenerator::generate();
    }
    
    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        echo RobotsBuilder::generate();
    }
    
    public function contact(): void
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'message' => $_POST['message'] ?? '',
        ];
        
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
        
        $errors = ContactMessage::validate($data);
        if (!empty($errors)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }
            $_SESSION['contact_errors'] = $errors;
            $_SESSION['contact_data'] = $data;
            header('Location: /?contact=error');
            exit;
        }
        
        if (ContactMessage::isRateLimited()) {
            $rateError = ['rate' => 'Aguarde um minuto antes de enviar outra mensagem.'];
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => $rateError]);
                exit;
            }
            $_SESSION['contact_errors'] = $rateError;
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
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
            exit;
        }
        
        $_SESSION['contact_success'] = 'Mensagem enviada com sucesso!';
        header('Location: /?contact=sent');
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
