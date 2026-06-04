<?php

declare(strict_types=1);

namespace App\Core;

class Config
{
    /** @var array<string, string> */
    private static array $cache = [];
    private static bool $loaded = false;
    
    public static function get(string $key, mixed $default = null): mixed
    {
        self::load();
        return self::$cache[$key] ?? $default;
    }
    
    public static function set(string $key, mixed $value): void
    {
        self::load();
        self::$cache[$key] = (string) $value;
        
        $existing = Database::fetchOne('SELECT 1 FROM settings WHERE `key` = ?', [$key]);
        if ($existing) {
            Database::update('settings', ['value' => (string) $value, 'updated_at' => date('Y-m-d H:i:s')], '`key` = ?', [$key]);
        } else {
            Database::insert('settings', [
                'key' => $key,
                'value' => (string) $value,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
    
    public static function load(): void
    {
        if (self::$loaded) {
            return;
        }
        
        try {
            $rows = Database::fetchAll('SELECT `key`, value FROM settings');
            foreach ($rows as $row) {
                self::$cache[$row['key']] = $row['value'];
            }
        } catch (\PDOException $e) {
            // Tabela pode não existir ainda
        }
        
        self::$loaded = true;
    }
    
    public static function all(): array
    {
        self::load();
        return self::$cache;
    }
}
