<?php

echo "=== FILE UPLOAD DIAGNOSTIC ===\n\n";

// 1. Check storage folder exists and writable
$newsFolder = 'storage/app/public/news';
echo "1️⃣  STORAGE FOLDER CHECK:\n";
if (file_exists($newsFolder)) {
    echo "   ✅ Folder exists: " . realpath($newsFolder) . "\n";
} else {
    echo "   ❌ Folder MISSING: " . realpath('storage/app/public') . "/news\n";
    exit(1);
}

if (is_writable($newsFolder)) {
    echo "   ✅ Folder is writable\n";
} else {
    echo "   ❌ Folder is NOT writable\n";
    echo "   Try: chmod -R 777 storage/app/public\n";
    exit(1);
}

// 2. Check PHP upload settings
echo "\n2️⃣  PHP UPLOAD SETTINGS:\n";
echo "   upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "   post_max_size: " . ini_get('post_max_size') . "\n";
echo "   max_file_uploads: " . ini_get('max_file_uploads') . "\n";
echo "   file_uploads enabled: " . (ini_get('file_uploads') ? 'YES' : 'NO') . "\n";

// 3. Test file create in storage
echo "\n3️⃣  TEST FILE WRITE:\n";
$testFile = $newsFolder . '/test_' . time() . '.txt';
$testContent = "Test upload at " . date('Y-m-d H:i:s');
if (file_put_contents($testFile, $testContent)) {
    echo "   ✅ Can write test file\n";
    echo "   File: " . $testFile . "\n";
    unlink($testFile);
    echo "   Test file deleted\n";
} else {
    echo "   ❌ Cannot write to storage folder\n";
    exit(1);
}

// 4. Test symlink
echo "\n4️⃣  SYMLINK CHECK:\n";
if (file_exists('public/storage') || is_link('public/storage')) {
    echo "   ✅ public/storage symlink exists\n";
    if (is_link('public/storage')) {
        $target = readlink('public/storage');
        echo "   Points to: " . $target . "\n";
    } else {
        echo "   (It's a junction or directory)\n";
    }
} else {
    echo "   ❌ public/storage NOT FOUND\n";
}

// 5. Check database connection
echo "\n5️⃣  DATABASE CHECK:\n";
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $result = $db->query("SELECT COUNT(*) as count FROM news WHERE tipe = 'video'");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo "   ✅ Database connected\n";
    echo "   Video news count: " . $row['count'] . "\n";
} catch (Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}

// 6. Summary
echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ DIAGNOSTIC COMPLETE\n\n";
echo "📋 NEXT STEPS:\n";
echo "   1. Click 'Tambah Berita' in admin\n";
echo "   2. Select 'Video' as tipe\n";
echo "   3. Choose an MP4 file\n";
echo "   4. Click 'Simpan Berita'\n";
echo "   5. Run this script again to check if file was uploaded\n";
echo "\n   If file still missing:\n";
echo "   - Check browser console (F12) for upload errors\n";
echo "   - Check your video file size (should be < 20MB)\n";
echo "   - Try a different video file\n";
echo "   - Check network tab for 413 or 422 errors\n";
?>
