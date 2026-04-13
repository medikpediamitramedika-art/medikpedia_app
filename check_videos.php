<?php

echo "=== MEDIKPEDIA VIDEO PLAYER VERIFICATION ===\n\n";

// Check storage folders
echo "📁 Checking storage folders...\n";
if (is_dir('storage/app/public/news')) {
    echo "✓ storage/app/public/news folder exists\n";
    $files = scandir('storage/app/public/news');
    $files = array_filter($files, fn($f) => !in_array($f, ['.', '..']));
    if (count($files) > 0) {
        echo "  Files found: " . count($files) . "\n";
        foreach (array_slice($files, 0, 5) as $file) {
            $size = filesize('storage/app/public/news/' . $file);
            echo "    • " . $file . " (" . round($size / 1024 / 1024, 2) . " MB)\n";
        }
        if (count($files) > 5) {
            echo "    ... and " . (count($files) - 5) . " more\n";
        }
    } else {
        echo "  (Empty folder)\n";
    }
} else {
    echo "✗ storage/app/public/news folder NOT found - Create it first!\n";
}

echo "\n";
echo "🔗 Checking public/storage symlink...\n";
if (is_link('public/storage')) {
    echo "✓ Symlink exists\n";
    $target = readlink('public/storage');
    echo "  Points to: " . $target . "\n";
} else {
    echo "✗ Symlink NOT found or not a link\n";
    echo "  Run: php artisan storage:link\n";
}

echo "\n";
echo "📰 Checking video news in database...\n";
require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\News;

$videos = News::where('tipe', 'video')->get(['id', 'judul', 'file', 'thumbnail']);
if ($videos->count() > 0) {
    echo "Found " . $videos->count() . " video news:\n";
    foreach ($videos as $news) {
        echo "  • [ID: " . $news->id . "] " . $news->judul . "\n";
        if ($news->file) {
            $filePath = 'storage/app/public/' . $news->file;
            if (file_exists($filePath)) {
                $size = filesize($filePath);
                echo "    File: ✓ " . $news->file . " (" . round($size / 1024 / 1024, 2) . " MB)\n";
            } else {
                echo "    File: ✗ " . $news->file . " (NOT FOUND)\n";
            }
        } else {
            echo "    File: EMPTY\n";
        }
        if ($news->thumbnail) {
            $thumbPath = 'storage/app/public/' . $news->thumbnail;
            if (file_exists($thumbPath)) {
                $size = filesize($thumbPath);
                echo "    Thumb: ✓ " . $news->thumbnail . " (" . round($size / 1024 / 1024, 2) . " MB)\n";
            } else {
                echo "    Thumb: ✗ " . $news->thumbnail . " (NOT FOUND)\n";
            }
        } else {
            echo "    Thumb: EMPTY\n";
        }
    }
} else {
    echo "No video news found in database.\n";
    echo "Upload a video through admin panel first at: /admin/news/create\n";
}

echo "\n";
echo "🎬 SUPPORTED VIDEO FORMATS:\n";
echo "  • MP4 (.mp4) - Most compatible\n";
echo "  • WebM (.webm) - Modern browsers\n";
echo "  • MOV (.mov) - Apple format\n";
echo "  • AVI (.avi) - Legacy format\n";
echo "  • MKV (.mkv) - Matroska format\n";

echo "\n";
echo "✅ CHECKLIST FOR VIDEO PLAYBACK:\n";
echo "  [ ] Video file exists in storage/app/public/news/\n";
echo "  [ ] Video type is set to 'video' when creating news\n";
echo "  [ ] public/storage symlink exists\n";
echo "  [ ] Browser supports the video format\n";
echo "  [ ] File is readable (check permissions)\n";

echo "\n";
echo "🔧 TROUBLESHOOTING:\n";
echo "  If videos still don't play:\n";
echo "  1. Clear browser cache (Ctrl+F5)\n";
echo "  2. Check browser console (F12) for errors\n";
echo "  3. Verify storage permissions: chmod -R 775 storage/\n";
echo "  4. Test video URL directly: /storage/news/filename.mp4\n";
echo "  5. Check file MIME type compatibility\n";
echo "\n";
?>
