<?php

declare(strict_types=1);

namespace App\Core;

class Seeder
{
    public static function run(): void
    {
        $db = Database::getInstance();
        
        // Create tables
        $db->exec('
            CREATE TABLE IF NOT EXISTS settings (
                key TEXT PRIMARY KEY,
                value TEXT NOT NULL,
                updated_at TEXT
            )
        ');
        
        $db->exec('
            CREATE TABLE IF NOT EXISTS pages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                slug TEXT NOT NULL UNIQUE,
                title TEXT NOT NULL,
                meta_title TEXT,
                meta_description TEXT,
                content_html TEXT,
                schema_type TEXT DEFAULT "WebPage",
                schema_data TEXT DEFAULT "{}",
                is_active INTEGER DEFAULT 1,
                created_at TEXT,
                updated_at TEXT
            )
        ');
        
        $db->exec('
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                created_at TEXT
            )
        ');
        
        $db->exec('
            CREATE TABLE IF NOT EXISTS addresses (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                page_id INTEGER,
                street TEXT,
                number TEXT,
                complement TEXT,
                neighborhood TEXT,
                city TEXT,
                state TEXT,
                zip TEXT,
                country TEXT DEFAULT "Brasil",
                lat TEXT,
                lng TEXT,
                created_at TEXT,
                updated_at TEXT
            )
        ');
        
        $db->exec('
            CREATE TABLE IF NOT EXISTS media (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                filename TEXT NOT NULL,
                original_name TEXT,
                mime_type TEXT,
                size_bytes INTEGER DEFAULT 0,
                width INTEGER,
                height INTEGER,
                path TEXT NOT NULL,
                created_at TEXT
            )
        ');
        
        $db->exec('
            CREATE TABLE IF NOT EXISTS contact_messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                phone TEXT,
                message TEXT NOT NULL,
                status TEXT DEFAULT "new",
                ip TEXT,
                created_at TEXT,
                updated_at TEXT
            )
        ');
        
        // Migrate pages table if needed (add content_blocks column)
        try {
            $db->exec('ALTER TABLE pages ADD COLUMN content_blocks TEXT');
        } catch (\PDOException $e) {
            // Column already exists
        }
        try {
            $db->exec('ALTER TABLE pages ADD COLUMN address_id INTEGER');
        } catch (\PDOException $e) {
            // Column already exists
        }
        
        // Seed default admin
        $existing = Database::fetchOne('SELECT 1 FROM users WHERE username = ?', ['admin']);
        if (!$existing) {
            Database::insert('users', [
                'username' => 'admin',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        
        // Seed default settings
        $defaults = [
            'site_title' => 'SEO Template PHP',
            'site_description' => 'Template completo para SEO com painel administrativo em PHP puro.',
            'site_url' => 'https://example.com',
            'contact_email' => 'contato@example.com',
            'mail_from' => 'noreply@example.com',
            'mail_from_name' => 'SEO Template',
        ];
        
        foreach ($defaults as $key => $value) {
            $existing = Database::fetchOne('SELECT 1 FROM settings WHERE key = ?', [$key]);
            if (!$existing) {
                Database::insert('settings', [
                    'key' => $key,
                    'value' => $value,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        
        // Seed homepage
        $existingHome = Database::fetchOne("SELECT 1 FROM pages WHERE slug = ''");
        if (!$existingHome) {
            Database::insert('pages', [
                'slug' => '',
                'title' => 'SEO Template PHP',
                'meta_title' => 'SEO Template PHP - Otimização Completa',
                'meta_description' => 'Template PHP para SEO com schema.org, sitemap, meta tags e painel administrativo.',
                'content_html' => '<p>Bem-vindo ao SEO Template PHP. Este é um template completo para criação de sites otimizados para motores de busca.</p>',
                'schema_type' => 'WebSite',
                'schema_data' => json_encode([
                    '@type' => 'WebSite',
                    'name' => 'SEO Template PHP',
                ]),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        
        // Seed about page (only on first run ever)
        $seederRan = Database::fetchOne("SELECT value FROM settings WHERE key = 'seeder_ran'");
        if (!$seederRan) {
            Database::insert('settings', [
                'key' => 'seeder_ran',
                'value' => '1',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            
            Database::insert('pages', [
                'slug' => 'sobre',
                'title' => 'Sobre Nós',
                'meta_title' => 'Sobre - SEO Template PHP',
                'meta_description' => 'Conheça mais sobre o SEO Template PHP.',
                'content_html' => '<p>Este template foi desenvolvido para oferecer uma base sólida para projetos web focados em SEO.</p><p>Recursos incluem: geração automática de sitemap, schema.org JSON-LD, meta tags otimizadas e painel administrativo completo.</p>',
                'schema_type' => 'AboutPage',
                'schema_data' => '{}',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
