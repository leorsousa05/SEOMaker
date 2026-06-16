<?php

declare(strict_types=1);

namespace App\Core;

class CanonicalRedirect
{
    private string $canonicalHost;
    private string $forceTrailingSlash;

    public function __construct(string $canonicalHost = 'auto', string $forceTrailingSlash = 'auto')
    {
        $this->canonicalHost = in_array($canonicalHost, ['www', 'non-www', 'auto'], true) ? $canonicalHost : 'auto';
        $this->forceTrailingSlash = in_array($forceTrailingSlash, ['1', '0', 'auto'], true) ? $forceTrailingSlash : 'auto';
    }

    public function shouldRedirect(string $host, string $path): ?string
    {
        $targetHost = $this->targetHost($host);
        $targetPath = $this->targetPath($path);

        if ($targetHost === null && $targetPath === null) {
            return null;
        }

        $finalHost = $targetHost ?? $host;
        $finalPath = $targetPath ?? $path;

        if ($finalHost === $host && $finalPath === $path) {
            return null;
        }

        $scheme = $this->currentScheme();
        return $scheme . '://' . $finalHost . $finalPath;
    }

    private function targetHost(string $host): ?string
    {
        if ($this->canonicalHost === 'auto') {
            return null;
        }

        $hasWww = str_starts_with(strtolower($host), 'www.');

        if ($this->canonicalHost === 'www' && !$hasWww) {
            return 'www.' . $host;
        }

        if ($this->canonicalHost === 'non-www' && $hasWww) {
            return substr($host, 4);
        }

        return null;
    }

    private function targetPath(string $path): ?string
    {
        if ($this->forceTrailingSlash === 'auto') {
            return null;
        }

        $parts = parse_url($path);
        $pathname = $parts['path'] ?? $path;
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

        if ($pathname === '' || $pathname === '/') {
            return null;
        }

        $hasTrailingSlash = str_ends_with($pathname, '/');
        $hasExtension = pathinfo($pathname, PATHINFO_EXTENSION) !== '';

        if ($hasExtension) {
            return null;
        }

        if ($this->forceTrailingSlash === '1' && !$hasTrailingSlash) {
            return $pathname . '/' . $query . $fragment;
        }

        if ($this->forceTrailingSlash === '0' && $hasTrailingSlash) {
            return substr($pathname, 0, -1) . $query . $fragment;
        }

        return null;
    }

    private function currentScheme(): string
    {
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return 'https';
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            return 'https';
        }

        return 'http';
    }
}
