<?php

declare(strict_types=1);

namespace App\Content;

use App\Core\Config;
use App\Core\Database;

class BlockEditor
{
    /**
     * @param array<int, array<string, mixed>> $blocks
     */
    public static function render(array $blocks, string $fallbackAddress = ''): string
    {
        $html = '';
        foreach ($blocks as $block) {
            $html .= self::renderBlock($block, $fallbackAddress);
        }
        return $html;
    }
    
    /**
     * @param array<string, mixed> $block
     */
    public static function renderBlock(array $block, string $fallbackAddress = ''): string
    {
        $type = $block['type'] ?? 'text';
        
        return match ($type) {
            'text' => self::renderText($block),
            'image' => self::renderImage($block),
            'gallery' => self::renderGallery($block),
            'video' => self::renderVideo($block),
            'map' => self::renderMap($block, $fallbackAddress),
            'cta' => self::renderCta($block),
            'faq' => self::renderFaq($block),
            'spacer' => self::renderSpacer($block),
            default => '',
        };
    }
    
    /**
     * @param array<string, mixed> $block
     */
    private static function renderText(array $block): string
    {
        $content = self::sanitizeHtml($block['content'] ?? '');
        return '<div class="block block-text">' . $content . '</div>';
    }
    
    /**
     * @param array<string, mixed> $block
     */
    private static function renderImage(array $block): string
    {
        $mediaId = $block['media_id'] ?? 0;
        $alt = self::e($block['alt'] ?? '');
        $caption = self::e($block['caption'] ?? '');
        $align = in_array($block['align'] ?? '', ['left', 'center', 'right']) ? $block['align'] : 'center';
        
        $path = '';
        if ($mediaId > 0) {
            $media = Database::fetchOne('SELECT path FROM media WHERE id = ?', [$mediaId]);
            if ($media) {
                $path = $media['path'];
            }
        }
        
        if (!$path && isset($block['src'])) {
            $path = $block['src'];
        }
        
        if (!$path) {
            return '';
        }
        
        $lazyAttr = (!isset($block['lazy']) || $block['lazy'] !== false) ? ' loading="lazy"' : '';
        $html = '<figure class="block block-image block-image--' . $align . '">';
        $html .= '<img src="' . self::e($path) . '" alt="' . $alt . '"' . $lazyAttr . '>';
        if ($caption) {
            $html .= '<figcaption>' . $caption . '</figcaption>';
        }
        $html .= '</figure>';
        return $html;
    }
    
    /**
     * @param array<string, mixed> $block
     */
    private static function renderGallery(array $block): string
    {
        $mediaIds = $block['media_ids'] ?? [];
        $columns = (int) ($block['columns'] ?? 3);
        $columns = max(1, min(6, $columns));
        
        if (empty($mediaIds)) {
            return '';
        }
        
        $lazyAttr = (!isset($block['lazy']) || $block['lazy'] !== false) ? ' loading="lazy"' : '';
        $html = '<div class="block block-gallery block-gallery--' . $columns . '">';
        
        $placeholders = implode(',', array_fill(0, count($mediaIds), '?'));
        $mediaItems = Database::fetchAll("SELECT id, path, original_name FROM media WHERE id IN ({$placeholders})", $mediaIds);
        
        foreach ($mediaItems as $item) {
            $html .= '<div class="gallery-item">';
            $html .= '<img src="' . self::e($item['path']) . '" alt="' . self::e($item['original_name']) . '"' . $lazyAttr . '>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        return $html;
    }
    
    /**
     * @param array<string, mixed> $block
     */
    private static function renderVideo(array $block): string
    {
        $url = $block['url'] ?? '';
        if (!$url) return '';
        
        $embedUrl = self::getVideoEmbedUrl($url);
        if (!$embedUrl) {
            return '';
        }
        
        $lazyAttr = (!isset($block['lazy']) || $block['lazy'] !== false) ? ' loading="lazy"' : '';
        return '<div class="block block-video"><div class="video-wrapper"><iframe src="' . self::e($embedUrl) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen' . $lazyAttr . '></iframe></div></div>';
    }
    
    /**
     * @param array<string, mixed> $block
     */
    private static function renderMap(array $block, string $fallbackAddress = ''): string
    {
        $addressId = $block['address_id'] ?? 0;
        $zoom = (int) ($block['zoom'] ?? 15);
        
        $address = null;
        if ($addressId > 0) {
            $address = Database::fetchOne('SELECT * FROM addresses WHERE id = ?', [$addressId]);
        }
        
        if ($address) {
            $addrStr = implode(', ', array_filter([
                $address['street'],
                $address['number'],
                $address['city'],
                $address['state'],
                $address['zip'],
                $address['country'],
            ]));
        } else {
            $addrStr = $fallbackAddress;
        }
        
        if (empty($addrStr)) {
            return '';
        }
        
        $embedUrl = 'https://maps.google.com/maps?q=' . urlencode($addrStr) . '&t=&z=' . $zoom . '&ie=UTF8&iwloc=&output=embed';
        $lazyAttr = (!isset($block['lazy']) || $block['lazy'] !== false) ? ' loading="lazy"' : '';
        
        return '<div class="block block-map"><div class="map-wrapper"><iframe src="' . self::e($embedUrl) . '" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"' . $lazyAttr . '></iframe></div></div>';
    }
    
    /**
     * @param array<string, mixed> $block
     */
    private static function renderCta(array $block): string
    {
        $text = self::e($block['text'] ?? 'Clique Aqui');
        $url = self::e($block['url'] ?? '#');
        $style = in_array($block['style'] ?? '', ['primary', 'secondary', 'outline']) ? $block['style'] : 'primary';
        
        return '<div class="block block-cta"><a href="' . $url . '" class="btn btn-' . $style . ' btn-lg">' . $text . '</a></div>';
    }
    
    /**
     * @param array<string, mixed> $block
     */
    private static function renderFaq(array $block): string
    {
        $items = $block['items'] ?? [];
        if (empty($items)) return '';
        
        $html = '<div class="block block-faq">';
        foreach ($items as $item) {
            $q = self::e($item['question'] ?? '');
            $a = self::sanitizeHtml($item['answer'] ?? '');
            if (!$q) continue;
            $html .= '<details class="faq-item"><summary class="faq-question">' . $q . '</summary><div class="faq-answer">' . $a . '</div></details>';
        }
        $html .= '</div>';
        return $html;
    }
    
    /**
     * @param array<string, mixed> $block
     */
    private static function renderSpacer(array $block): string
    {
        $height = (int) ($block['height'] ?? 40);
        $height = max(8, min(200, $height));
        return '<div class="block block-spacer" style="height:' . $height . 'px"></div>';
    }
    
    public static function sanitizeHtml(string $html): string
    {
        // Remove scripts and dangerous attributes
        $html = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $html);
        $html = preg_replace('/<iframe[^>]*>.*?<\/iframe>/is', '', $html);
        $html = preg_replace('/ on\w+=["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/javascript:/i', '', $html);
        return $html;
    }
    
    public static function getVideoEmbedUrl(string $url): ?string
    {
        // YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        // Vimeo
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }
        return null;
    }
    
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function defaultBlocks(): array
    {
        return [
            ['type' => 'text', 'content' => '<p>Comece a editar o conteúdo da sua página...</p>'],
        ];
    }
    
    private static function e(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}
