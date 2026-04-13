<?php

echo "=== VIDEO PLAYBACK TROUBLESHOOTING TOOL ===\n\n";

// Check if database file exists
$dbPath = 'database/database.sqlite';
if (!file_exists($dbPath)) {
    echo "❌ Database not found at: " . realpath('.') . "\\" . $dbPath . "\n";
    echo "   Make sure you're in the correct directory!\n\n";
    exit(1);
}

// Connect to SQLite database directly
try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo "❌ Cannot connect to database: " . $e->getMessage() . "\n\n";
    exit(1);
}

echo "🎬 CHECKING VIDEO BERITA IN DATABASE...\n\n";

// Get videos from database
$stmt = $db->query("SELECT id, judul, file, thumbnail, tipe, is_published FROM news WHERE tipe = 'video' ORDER BY id DESC");
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($videos)) {
    echo "❌ NO VIDEO NEWS FOUND\n";
    echo "   Upload a video first at: /admin/news/create\n\n";
    exit;
}

echo "✅ Found " . count($videos) . " video news\n\n";

foreach ($videos as $news) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📰 NEWS ID: " . $news['id'] . "\n";
    echo "📝 JUDUL: " . $news['judul'] . "\n";
    echo "🎬 TIPE: " . $news['tipe'] . "\n";
    echo "📅 STATUS: " . ($news['is_published'] ? "✅ PUBLISHED" : "⏳ DRAFT") . "\n\n";

    // Check file/media
    echo "📂 MEDIA FILE:\n";
    if ($news['file']) {
        echo "  Database: " . $news['file'] . "\n";
        
        $fullPath = 'storage/app/public/' . $news['file'];
        if (file_exists($fullPath)) {
            $size = filesize($fullPath);
            $ext = pathinfo($news['file'], PATHINFO_EXTENSION);
            echo "  ✅ FILE EXISTS: " . $fullPath . "\n";
            echo "     Size: " . round($size / 1024 / 1024, 2) . " MB\n";
            echo "     Extension: ." . strtoupper($ext) . "\n";
            echo "     Readable: " . (is_readable($fullPath) ? "✅ YES" : "❌ NO") . "\n";
            echo "     Public URL: /storage/" . $news['file'] . "\n";
        } else {
            echo "  ❌ FILE NOT FOUND: " . $fullPath . "\n";
            echo "     Please re-upload the video\n";
        }
    } else {
        echo "  ❌ NO FILE: File column is empty\n";
    }

    echo "\n";

    // Check thumbnail
    echo "🖼️  THUMBNAIL:\n";
    if ($news['thumbnail']) {
        echo "  Database: " . $news['thumbnail'] . "\n";
        
        $thumbPath = 'storage/app/public/' . $news['thumbnail'];
        if (file_exists($thumbPath)) {
            $size = filesize($thumbPath);
            echo "  ✅ THUMBNAIL EXISTS: " . $thumbPath . "\n";
            echo "     Size: " . round($size / 1024, 2) . " KB\n";
        } else {
            echo "  ⚠️  THUMBNAIL NOT FOUND: " . $thumbPath . "\n";
        }
    } else {
        echo "  ℹ️  No thumbnail (will use fallback emoji)\n";
    }

    echo "\n";

    // Test URL accessibility
    echo "🌐 TEST URL ACCESS:\n";
    if ($news['file']) {
        echo "  Try visiting: http://localhost:8000/storage/" . $news['file'] . "\n";
        echo "  (Should play or download video, NOT 404)\n";
    }

    echo "\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "🔧 TROUBLESHOOTING STEPS:\n\n";

echo "1️⃣  CLEAR BROWSER CACHE\n";
echo "   Press: Ctrl + Shift + Delete\n";
echo "   Then hard refresh: Ctrl + F5\n\n";

echo "2️⃣  VERIFY STORAGE SYMLINK\n";
echo "   Check: public/storage exists\n";
echo "   If missing, run: php artisan storage:link\n\n";

echo "3️⃣  CHECK FILE PERMISSIONS\n";
echo "   Run: chmod -R 775 storage/app/public\n\n";

echo "4️⃣  TEST DIRECT FILE ACCESS\n";
echo "   Copy the URL from above\n";
echo "   Paste in new browser tab\n";
echo "   File should play or prompt download\n\n";

echo "5️⃣  CHECK BROWSER CONSOLE\n";
echo "   Press F12 → Console tab\n";
echo "   Look for CORS or 404 errors\n";
echo "   Pay attention to Network tab\n\n";

echo "6️⃣  VERIFY VIDEO FORMAT\n";
echo "   Supported: MP4, WebM, MOV, AVI, MKV\n";
echo "   Best compatibility: MP4\n";
echo "   Max size: 20MB\n\n";

echo "7️⃣  LATEST TV INFO FROM DATABASE\n";
$latest = $db->query("SELECT id, judul, file, is_published FROM news WHERE tipe = 'video' ORDER BY created_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
if ($latest) {
    echo "  ✅ Latest video:\n";
    echo "     ID: " . $latest['id'] . "\n";
    echo "     Title: " . $latest['judul'] . "\n";
    echo "     File: " . ($latest['file'] ?: 'NULL') . "\n";
    echo "     Published: " . ($latest['is_published'] ? 'YES' : 'NO') . "\n";
} else {
    echo "  ℹ️  No videos in database\n";
}

echo "\n";
echo "✅ DEBUGGING COMPLETE - Check the info above!\n";
echo "📸 If issue persists, screenshot the errors and file paths shown above.\n";
echo "🔗 Symlink check:\n";
if (is_link('public/storage') || is_dir('public/storage')) {
    echo "   ✅ public/storage exists\n";
} else {
    echo "   ❌ public/storage MISSING - Run: php artisan storage:link\n";
}
?>
