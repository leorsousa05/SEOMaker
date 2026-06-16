<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Core\CanonicalRedirect;

function assertCanonical(bool $condition, string $message): void
{
    if (!$condition) {
        throw new \RuntimeException("FAIL: {$message}");
    }
    echo "PASS: {$message}\n";
}

// auto: no redirect
$redirect = new CanonicalRedirect('auto', 'auto');
assertCanonical($redirect->shouldRedirect('example.com', '/page/sobre') === null, 'auto host does not redirect');
assertCanonical($redirect->shouldRedirect('example.com', '/page/sobre/') === null, 'auto trailing slash does not redirect');

// www: redirect non-www to www
$redirect = new CanonicalRedirect('www', 'auto');
assertCanonical($redirect->shouldRedirect('example.com', '/page/sobre') === 'http://www.example.com/page/sobre', 'non-www redirects to www');
assertCanonical($redirect->shouldRedirect('www.example.com', '/page/sobre') === null, 'www host does not redirect');

// non-www: redirect www to non-www
$redirect = new CanonicalRedirect('non-www', 'auto');
assertCanonical($redirect->shouldRedirect('www.example.com', '/page/sobre') === 'http://example.com/page/sobre', 'www redirects to non-www');
assertCanonical($redirect->shouldRedirect('example.com', '/page/sobre') === null, 'non-www host does not redirect');

// trailing slash: add
$redirect = new CanonicalRedirect('auto', '1');
assertCanonical($redirect->shouldRedirect('example.com', '/page/sobre') === 'http://example.com/page/sobre/', 'adds trailing slash');
assertCanonical($redirect->shouldRedirect('example.com', '/page/sobre/') === null, 'already has trailing slash does not redirect');

// trailing slash: remove
$redirect = new CanonicalRedirect('auto', '0');
assertCanonical($redirect->shouldRedirect('example.com', '/page/sobre/') === 'http://example.com/page/sobre', 'removes trailing slash');
assertCanonical($redirect->shouldRedirect('example.com', '/page/sobre') === null, 'already without trailing slash does not redirect');

// combined www + trailing slash
$redirect = new CanonicalRedirect('www', '1');
assertCanonical($redirect->shouldRedirect('example.com', '/page/sobre') === 'http://www.example.com/page/sobre/', 'www and trailing slash combined');

// no loop when already canonical
$redirect = new CanonicalRedirect('www', '1');
assertCanonical($redirect->shouldRedirect('www.example.com', '/page/sobre/') === null, 'already canonical does not redirect');

// invalid config values treated as auto
$redirect = new CanonicalRedirect('invalid', 'invalid');
assertCanonical($redirect->shouldRedirect('example.com', '/page/sobre') === null, 'invalid config treated as auto');

// file paths (with extension) ignored for trailing slash
$redirect = new CanonicalRedirect('auto', '1');
assertCanonical($redirect->shouldRedirect('example.com', '/assets/style.css') === null, 'file paths ignore trailing slash');

// root path ignored for trailing slash
$redirect = new CanonicalRedirect('auto', '0');
assertCanonical($redirect->shouldRedirect('example.com', '/') === null, 'root path ignored for trailing slash');
