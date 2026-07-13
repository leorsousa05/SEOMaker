<?php

declare(strict_types=1);

// Namespace shadowing for move_uploaded_file to allow CLI testing
namespace App\Content {
    function move_uploaded_file(string $from, string $to): bool {
        return copy($from, $to);
    }
}

namespace {
    require_once __DIR__ . '/../../src/autoload.php';

    use App\Content\MediaManager;
    use App\Core\Database;

    function assertMedia(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new \RuntimeException("FAIL: {$message}");
        }
        echo "PASS: {$message}\n";
    }

    // Setup temporary files directory for testing
    $testTmpDir = __DIR__ . '/../tmp';
    if (!is_dir($testTmpDir)) {
        mkdir($testTmpDir, 0755, true);
    }

    // Helper to generate a 1x1 transparent GIF image file
    $generateGif = function (string $path) {
        $gifBase64 = 'R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
        file_put_contents($path, base64_decode($gifBase64));
    };

    // Helper to generate a dummy non-image text file
    $generateText = function (string $path) {
        file_put_contents($path, 'not an image file content');
    };

    echo "=== Running MediaManager Tests ===\n";

    // Test Case 1: Sanitization of Filenames
    $sanitized = (new \ReflectionClass(MediaManager::class))
        ->getMethod('sanitizeFilename');
    $sanitized->setAccessible(true);
    
    $name1 = $sanitized->invoke(null, 'Meu Gato Lindo (2026).PNG');
    assertMedia($name1 === 'meu_gato_lindo_2026.png', 'sanitizeFilename normalization and lowercasing');

    $name2 = $sanitized->invoke(null, 'test file#name$.jpg');
    assertMedia($name2 === 'test_filename.jpg', 'sanitizeFilename strips special chars');

    // Test Case 2: Validation of Allowed Image Extensions
    $gifPath = $testTmpDir . '/test.gif';
    $generateGif($gifPath);
    assertMedia(MediaManager::isValidImage($gifPath, 'image/gif'), 'valid GIF is accepted');

    $txtPath = $testTmpDir . '/test.txt';
    $generateText($txtPath);
    assertMedia(!MediaManager::isValidImage($txtPath, 'text/plain'), 'invalid text MIME type is rejected');
    assertMedia(!MediaManager::isValidImage($txtPath, 'image/gif'), 'file with invalid image header is rejected even if MIME is forced');

    // Test Case 3: Upload size validation limits
    $largePath = $testTmpDir . '/large.gif';
    $generateGif($largePath);
    
    // Test small file upload
    $fileSmall = [
        'name' => 'small.gif',
        'type' => 'image/gif',
        'tmp_name' => $gifPath,
        'error' => UPLOAD_ERR_OK,
        'size' => filesize($gifPath),
    ];
    $uploadResult = MediaManager::upload($fileSmall);
    assertMedia($uploadResult['success'] === true, 'upload of a valid small image succeeds');
    assertMedia(isset($uploadResult['id']), 'upload returns a database ID');
    
    $createdId = $uploadResult['id'];

    // Test file size limit error (> 5MB)
    $fileLarge = [
        'name' => 'large.gif',
        'type' => 'image/gif',
        'tmp_name' => $largePath,
        'error' => UPLOAD_ERR_OK,
        'size' => 6 * 1024 * 1024, // 6MB
    ];
    $largeResult = MediaManager::upload($fileLarge);
    assertMedia($largeResult['success'] === false, 'upload of files > 5MB is rejected');
    assertMedia($largeResult['error'] === 'Arquivo muito grande. Máximo 5MB.', 'correct error message for size limits');

    // Test Case 4: Thumbnail Generation & Paths
    if (extension_loaded('gd')) {
        $savedPath = __DIR__ . '/../../public' . $uploadResult['path'];
        assertMedia(file_exists($savedPath), 'uploaded original file exists on disk');
        
        $thumbPath = dirname($savedPath) . '/thumb_' . basename($savedPath);
        assertMedia(file_exists($thumbPath), 'generated thumbnail exists on disk');
        
        $imgInfo = getimagesize($thumbPath);
        assertMedia($imgInfo[0] === 300 && $imgInfo[1] === 300, 'thumbnail size is exactly 300x300px');
    } else {
        echo "GD extension not loaded. Skipping thumbnail size check.\n";
    }

    // Test Case 5: Deletion of media record and physical files
    $mediaRecord = Database::fetchOne('SELECT * FROM media WHERE id = ?', [$createdId]);
    assertMedia($mediaRecord !== false, 'media record exists in database');
    
    $originalFileDiskPath = __DIR__ . '/../../public' . $mediaRecord['path'];
    $thumbFileDiskPath = dirname($originalFileDiskPath) . '/thumb_' . basename($originalFileDiskPath);
    
    assertMedia(file_exists($originalFileDiskPath), 'original file exists before deletion');

    $deleted = MediaManager::delete($createdId);
    assertMedia($deleted === true, 'MediaManager::delete returns true on success');

    $dbCheck = Database::fetchOne('SELECT * FROM media WHERE id = ?', [$createdId]);
    assertMedia($dbCheck === false, 'media record is deleted from database');
    assertMedia(!file_exists($originalFileDiskPath), 'original physical file is deleted from disk');
    assertMedia(!file_exists($thumbFileDiskPath), 'thumbnail physical file is deleted from disk');

    // Clean up temporary files
    if (file_exists($gifPath)) unlink($gifPath);
    if (file_exists($txtPath)) unlink($txtPath);
    if (file_exists($largePath)) unlink($largePath);
    if (is_dir($testTmpDir)) rmdir($testTmpDir);

    echo "=== MediaManager Tests Completed ===\n";
}
