<?php

declare(strict_types=1);

namespace App\Core;

class FileCache
{
    public const CACHE_DIR = __DIR__ . '/../../config/cache/';

    public static function get(string $key): ?string
    {
        $path = self::pathFor($key);
        if (!is_file($path)) {
            return null;
        }

        $content = @file_get_contents($path);
        if ($content === false || trim($content) === '') {
            return null;
        }

        return $content;
    }

    public static function set(string $key, string $value): bool
    {
        $path = self::pathFor($key);
        $dir = dirname($path);

        if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
            error_log("FileCache: nao foi possivel criar diretorio {$dir}");
            return false;
        }

        $tmp = $path . '.' . uniqid('tmp', true);
        if (@file_put_contents($tmp, $value, LOCK_EX) === false) {
            error_log("FileCache: falha ao escrever cache temporario {$tmp}");
            return false;
        }

        if (!@rename($tmp, $path)) {
            @unlink($tmp);
            error_log("FileCache: falha ao renomear cache para {$path}");
            return false;
        }

        return true;
    }

    public static function delete(string $key): bool
    {
        $path = self::pathFor($key);
        if (!is_file($path)) {
            return true;
        }

        return @unlink($path);
    }

    public static function clear(): bool
    {
        if (!is_dir(self::CACHE_DIR)) {
            return true;
        }

        $files = @scandir(self::CACHE_DIR);
        if ($files === false) {
            return false;
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            @unlink(self::CACHE_DIR . $file);
        }

        return true;
    }

    private static function pathFor(string $key): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9._-]/', '_', $key);
        if ($safe === '') {
            throw new \InvalidArgumentException('Cache key inválida');
        }

        return self::CACHE_DIR . $safe;
    }
}
