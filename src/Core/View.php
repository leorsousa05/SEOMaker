<?php

declare(strict_types=1);

namespace App\Core;

class View
{
    private static ?string $layout = null;
    private static string $content = '';
    /** @var array<string, mixed> */
    private static array $shared = [];
    
    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }
    
    public static function layout(string $layout): void
    {
        self::$layout = $layout;
    }
    
    public static function render(string $template, array $data = []): string
    {
        extract(array_merge(self::$shared, $data));
        
        ob_start();
        $templatePath = __DIR__ . '/../../templates/' . $template . '.php';
        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template not found: {$template}");
        }
        require $templatePath;
        self::$content = ob_get_clean();
        
        if (self::$layout !== null) {
            $layoutPath = __DIR__ . '/../../templates/' . self::$layout . '.php';
            if (!file_exists($layoutPath)) {
                throw new \RuntimeException("Layout not found: " . self::$layout);
            }
            $content = self::$content;
            extract(array_merge(self::$shared, $data));
            ob_start();
            require $layoutPath;
            return ob_get_clean();
        }
        
        return self::$content;
    }
    
    public static function content(): string
    {
        return self::$content;
    }
    
    public static function partial(string $template, array $data = []): string
    {
        extract(array_merge(self::$shared, $data));
        ob_start();
        $path = __DIR__ . '/../../templates/' . $template . '.php';
        if (file_exists($path)) {
            require $path;
        }
        return ob_get_clean();
    }
    
    public static function escape(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}
