<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Content\MediaManager;
use App\Core\Database;
use App\Core\Seeder;

if (!function_exists('assertTrue')) {
    function assertTrue(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new \RuntimeException("FAIL: {$message}");
        }
        echo "PASS: {$message}\n";
    }
}

if (!function_exists('assertEquals')) {
    function assertEquals($expected, $actual, string $message): void
    {
        if ($expected !== $actual) {
            throw new \RuntimeException("FAIL: {$message} — expected " . var_export($expected, true) . ', got ' . var_export($actual, true));
        }
        echo "PASS: {$message}\n";
    }
}

if (!function_exists('assertNotNull')) {
    function assertNotNull($actual, string $message): void
    {
        if ($actual === null) {
            throw new \RuntimeException("FAIL: {$message} — expected not null");
        }
        echo "PASS: {$message}\n";
    }
}

if (!function_exists('assertNull')) {
    function assertNull($actual, string $message): void
    {
        if ($actual !== null) {
            throw new \RuntimeException("FAIL: {$message} — expected null, got " . var_export($actual, true));
        }
        echo "PASS: {$message}\n";
    }
}

Seeder::run();

$testUploadIds = [];

function createTestImage(string $extension = 'png'): string
{
    if (!extension_loaded('gd')) {
        throw new \RuntimeException('GD extension is required to create test images');
    }

    $tmpFile = tempnam(sys_get_temp_dir(), 'media_test_') . '.' . $extension;
    $image = imagecreatetruecolor(100, 100);
    $bgColor = imagecolorallocate($image, 255, 0, 0);
    imagefill($image, 0, 0, $bgColor);

    $saved = match ($extension) {
        'png' => imagepng($image, $tmpFile),
        'jpg' => imagejpeg($image, $tmpFile),
        'gif' => imagegif($image, $tmpFile),
        'webp' => function_exists('imagewebp') ? imagewebp($image, $tmpFile) : false,
        default => false,
    };

    imagedestroy($image);

    if (!$saved) {
        throw new \RuntimeException("Failed to create test image: {$extension}");
    }

    return $tmpFile;
}

function cleanupUploads(): void
{
    global $testUploadIds;

    foreach ($testUploadIds as $id) {
        $media = Database::fetchOne('SELECT * FROM media WHERE id = ?', [$id]);
        if ($media) {
            $fullPath = __DIR__ . '/../../public' . $media['path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $thumbPath = dirname($fullPath) . '/thumb_' . basename($fullPath);
            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }
            Database::delete('media', 'id = ?', [$id]);
        }
    }

    $testUploadIds = [];
}

try {
    // Test: upload valid PNG image
    $tmpFile = createTestImage('png');
    $upload = [
        'tmp_name' => $tmpFile,
        'name' => 'test-image.png',
        'type' => 'image/png',
        'error' => UPLOAD_ERR_OK,
        'size' => filesize($tmpFile),
    ];

    $result = MediaManager::upload($upload);
    assertTrue($result['success'], 'upload valid PNG returns success');
    assertNotNull($result['id'] ?? null, 'upload valid PNG returns id');
    assertTrue(str_contains($result['filename'] ?? '', 'test-image.png'), 'upload preserves original filename');
    assertTrue(file_exists(__DIR__ . '/../../public' . $result['path']), 'uploaded file exists on disk');

    $testUploadIds[] = (int) $result['id'];
    $uploadedId = (int) $result['id'];

    // Test: thumbnail generated for uploaded image
    if (extension_loaded('gd')) {
        assertNotNull($result['thumb'] ?? null, 'upload generates thumbnail path');
        assertTrue(file_exists(__DIR__ . '/../../public' . $result['thumb']), 'thumbnail file exists on disk');
    }

    // Test: find returns Media object
    $media = MediaManager::find($uploadedId);
    assertNotNull($media, 'find returns media object');
    assertEquals($uploadedId, $media->id, 'find returns correct media id');
    assertEquals('image/png', $media->mime_type, 'find returns correct mime type');

    // Test: delete removes file and record
    $deleted = MediaManager::delete($uploadedId);
    assertTrue($deleted, 'delete returns true');
    assertNull(MediaManager::find($uploadedId), 'deleted media is not found');
    assertTrue(!file_exists(__DIR__ . '/../../public' . $result['path']), 'uploaded file removed after delete');
    if (extension_loaded('gd') && !empty($result['thumb'])) {
        assertTrue(!file_exists(__DIR__ . '/../../public' . $result['thumb']), 'thumbnail removed after delete');
    }

    // Remove from cleanup list since already deleted
    array_pop($testUploadIds);

    // Test: invalid mime type rejected
    $textFile = tempnam(sys_get_temp_dir(), 'media_test_') . '.txt';
    file_put_contents($textFile, 'not an image');
    $invalidUpload = [
        'tmp_name' => $textFile,
        'name' => 'invalid.txt',
        'type' => 'text/plain',
        'error' => UPLOAD_ERR_OK,
        'size' => filesize($textFile),
    ];
    $invalidResult = MediaManager::upload($invalidUpload);
    assertTrue(!$invalidResult['success'], 'invalid mime type rejected');
    assertTrue(str_contains($invalidResult['error'] ?? '', 'não permitido'), 'invalid mime type error message');
    unlink($textFile);

    // Test: file too large rejected
    $largeFile = createTestImage('png');
    $largeUpload = [
        'tmp_name' => $largeFile,
        'name' => 'large-image.png',
        'type' => 'image/png',
        'error' => UPLOAD_ERR_OK,
        'size' => 10 * 1024 * 1024, // 10MB, bigger than limit
    ];
    $largeResult = MediaManager::upload($largeUpload);
    assertTrue(!$largeResult['success'], 'oversized file rejected');
    assertTrue(str_contains($largeResult['error'] ?? '', 'Máximo 5MB'), 'oversized file error message');
    unlink($largeFile);

    // Test: generateThumbnail helper
    if (extension_loaded('gd')) {
        $thumbDir = __DIR__ . '/../../public/uploads/test_thumbnails';
        if (!is_dir($thumbDir)) {
            mkdir($thumbDir, 0755, true);
        }
        $thumbSource = $thumbDir . '/source.png';

        $img = imagecreatetruecolor(100, 100);
        $bgColor = imagecolorallocate($img, 0, 255, 0);
        imagefill($img, 0, 0, $bgColor);
        imagepng($img, $thumbSource);
        imagedestroy($img);

        $thumbName = basename($thumbSource);
        $thumbResult = MediaManager::generateThumbnail($thumbSource, $thumbDir, $thumbName);
        assertNotNull($thumbResult, 'generateThumbnail returns result');
        assertTrue(file_exists($thumbResult['path']), 'generateThumbnail creates file');
        assertTrue(str_starts_with($thumbResult['url'], '/uploads'), 'generateThumbnail returns public url');
        unlink($thumbSource);
        unlink($thumbResult['path']);
    }

    // Test: list pagination
    $beforeCount = MediaManager::count();
    $tmpListFile = createTestImage('png');
    $listUpload = [
        'tmp_name' => $tmpListFile,
        'name' => 'list-image.png',
        'type' => 'image/png',
        'error' => UPLOAD_ERR_OK,
        'size' => filesize($tmpListFile),
    ];
    $listResult = MediaManager::upload($listUpload);
    assertTrue($listResult['success'], 'list test upload succeeds');
    $testUploadIds[] = (int) $listResult['id'];

    $items = MediaManager::list(1, 10);
    assertTrue(count($items) >= 1, 'list returns at least one item');
    assertEquals($beforeCount + 1, MediaManager::count(), 'count increments after upload');

    echo "\nAll MediaManager tests passed.\n";
} finally {
    cleanupUploads();
}
