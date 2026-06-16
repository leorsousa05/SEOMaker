<?php

declare(strict_types=1);

namespace App\Admin;

use App\Content\BlockEditor;
use App\Core\Database;
use App\Core\FileCache;
use App\Core\View;
use App\Models\Address;
use App\Models\Page;
use App\Seo\LocalBusinessSchema;
use App\Seo\RobotsBuilder;
use App\Seo\SchemaFormBuilder;
use App\Seo\SitemapGenerator;

class PagesController
{
    public function index(): void
    {
        AuthController::requireAuth();
        
        $pages = Database::fetchAll('SELECT * FROM pages ORDER BY id DESC');
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        
        View::layout('admin/layout');
        echo View::render('admin/pages', [
            'pages' => $pages,
            'flash' => $flash,
            'pageTitle' => 'Páginas',
            'activeNav' => 'pages',
        ]);
    }
    
    public function edit(array $params): void
    {
        AuthController::requireAuth();
        
        $id = isset($params['id']) ? (int) $params['id'] : 0;
        $page = $id > 0 ? Database::fetchOne('SELECT * FROM pages WHERE id = ?', [$id]) : null;
        
        $schemaTypes = SchemaFormBuilder::types();
        
        // Load address if page has one
        $address = null;
        if ($page && !empty($page['address_id'])) {
            $addrData = Database::fetchOne('SELECT * FROM addresses WHERE id = ?', [$page['address_id']]);
            if ($addrData) {
                $address = Address::fromArray($addrData);
            }
        }
        
        // Load OG image path if page has one
        if ($page && !empty($page['og_image_id'])) {
            $mediaData = Database::fetchOne('SELECT path FROM media WHERE id = ?', [$page['og_image_id']]);
            if ($mediaData) {
                $page['og_image_path'] = $mediaData['path'];
            }
        }
        
        // Parse content blocks
        $contentBlocks = [];
        if ($page && !empty($page['content_blocks'])) {
            $decoded = json_decode($page['content_blocks'], true);
            if (is_array($decoded)) {
                $contentBlocks = $decoded;
            }
        }
        if (empty($contentBlocks)) {
            $contentBlocks = BlockEditor::defaultBlocks();
        }
        
        View::layout('admin/layout');
        echo View::render('admin/pages_edit', [
            'page' => $page,
            'schemaTypes' => $schemaTypes,
            'address' => $address,
            'contentBlocks' => $contentBlocks,
            'pageTitle' => $isEdit ? 'Editar Página' : 'Nova Página',
            'activeNav' => 'pages',
            'headerActions' => '<a href="/admin/pages" class="btn btn-ghost">← Voltar</a>',
        ]);
    }
    
    public function save(): void
    {
        AuthController::requireAuth();
        
        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $schemaType = $_POST['schema_type'] ?? 'WebPage';
        
        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $autoSlug = isset($_POST['auto_slug']) || $slug === '';
        
        if ($autoSlug && $slug === '' && $title !== '') {
            $slug = Page::generateSlug($title);
        }
        
        $validationData = [
            'title' => $title,
            'slug' => $slug,
        ];
        $errors = Page::validate($validationData, $id > 0 ? $id : null);
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            $redirect = $id > 0 ? '/admin/pages/edit/' . $id : '/admin/pages/edit';
            header('Location: ' . $redirect);
            exit;
        }
        
        // Build schema JSON from form fields if present
        $schemaData = $_POST['schema_data'] ?? '{}';
        $fields = SchemaFormBuilder::fieldsForType($schemaType);
        if (!empty($fields)) {
            $hasSchemaFields = false;
            foreach ($fields as $field) {
                if (isset($_POST[$field['name']]) && $_POST[$field['name']] !== '') {
                    $hasSchemaFields = true;
                    break;
                }
            }
            if ($hasSchemaFields) {
                $schemaData = SchemaFormBuilder::buildJson($_POST, $schemaType);
            }
        }
        
        // Build content blocks from editor
        $contentBlocks = $_POST['content_blocks'] ?? '';
        $contentHtml = '';
        $blockErrors = [];
        if ($contentBlocks) {
            $blocks = json_decode($contentBlocks, true);
            if (is_array($blocks)) {
                $blockErrors = BlockEditor::validateBlocks($blocks);
                if (empty($blockErrors)) {
                    $contentHtml = BlockEditor::render($blocks);
                }
            }
        }
        
        if (!empty($blockErrors)) {
            $_SESSION['form_errors'] = ['content_blocks' => 'Corrija os erros nos blocos de conteúdo.'] + $blockErrors;
            $_SESSION['form_data'] = $_POST;
            $redirect = $id > 0 ? '/admin/pages/edit/' . $id : '/admin/pages/edit';
            header('Location: ' . $redirect);
            exit;
        }
        
        $data = [
            'slug' => $slug,
            'title' => $title,
            'meta_title' => $_POST['meta_title'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'meta_robots' => self::sanitizeMetaRobots($_POST['meta_robots'] ?? []),
            'canonical_url' => self::sanitizeCanonicalUrl($_POST['canonical_url'] ?? ''),
            'og_image_id' => isset($_POST['og_image_id']) && $_POST['og_image_id'] !== '' ? (int) $_POST['og_image_id'] : null,
            'content_html' => $contentHtml,
            'content_blocks' => $contentBlocks,
            'schema_type' => $schemaType,
            'schema_data' => $schemaData,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        if ($id > 0) {
            Database::update('pages', $data, 'id = ?', [$id]);
            $pageId = $id;
            $_SESSION['flash'] = 'Página atualizada com sucesso!';
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $pageId = Database::insert('pages', $data);
            $_SESSION['flash'] = 'Página criada com sucesso!';
        }
        
        // Invalidate sitemap and robots caches
        FileCache::delete(SitemapGenerator::CACHE_KEY);
        FileCache::delete(RobotsBuilder::CACHE_KEY);

        // Save/update address
        if (isset($_POST['address_street']) && $_POST['address_street'] !== '') {
            $addrData = [
                'street' => $_POST['address_street'] ?? '',
                'number' => $_POST['address_number'] ?? '',
                'complement' => $_POST['address_complement'] ?? '',
                'neighborhood' => $_POST['address_neighborhood'] ?? '',
                'city' => $_POST['address_city'] ?? '',
                'state' => $_POST['address_state'] ?? '',
                'zip' => $_POST['address_zip'] ?? '',
                'country' => $_POST['address_country'] ?? 'Brasil',
                'lat' => $_POST['address_lat'] ?? null,
                'lng' => $_POST['address_lng'] ?? null,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            $existingAddr = Database::fetchOne('SELECT id FROM addresses WHERE page_id = ?', [$pageId]);
            if ($existingAddr) {
                Database::update('addresses', $addrData, 'id = ?', [$existingAddr['id']]);
                $addressId = (int) $existingAddr['id'];
            } else {
                $addrData['page_id'] = $pageId;
                $addrData['created_at'] = date('Y-m-d H:i:s');
                $addressId = Database::insert('addresses', $addrData);
            }
            
            // Update page address_id
            Database::update('pages', ['address_id' => $addressId], 'id = ?', [$pageId]);
        }
        
        header('Location: /admin/pages');
        exit;
    }
    
    private static function sanitizeCanonicalUrl(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }
        if (preg_match('/^(https?:\/\/|\/)/i', $url) !== 1) {
            return null;
        }
        if (stripos($url, 'javascript:') === 0) {
            return null;
        }
        return $url;
    }
    
    /**
     * @param array<int, string> $values
     */
    private static function sanitizeMetaRobots(array $values): string
    {
        $allowed = ['index', 'noindex', 'follow', 'nofollow'];
        $tokens = [];
        foreach ($values as $value) {
            $value = (string) $value;
            if (in_array($value, $allowed, true)) {
                $tokens[] = $value;
            }
        }
        $index = in_array('noindex', $tokens, true) ? 'noindex' : 'index';
        $follow = in_array('nofollow', $tokens, true) ? 'nofollow' : 'follow';
        return $index . ', ' . $follow;
    }
    
    public function delete(array $params): void
    {
        AuthController::requireAuth();
        
        $id = isset($params['id']) ? (int) $params['id'] : 0;
        if ($id > 0) {
            // Delete associated address
            Database::delete('addresses', 'page_id = ?', [$id]);
            Database::delete('pages', 'id = ?', [$id]);
            $_SESSION['flash'] = 'Página removida com sucesso!';

            // Invalidate sitemap and robots caches
            FileCache::delete(SitemapGenerator::CACHE_KEY);
            FileCache::delete(RobotsBuilder::CACHE_KEY);
        }

        header('Location: /admin/pages');
        exit;
    }
}
