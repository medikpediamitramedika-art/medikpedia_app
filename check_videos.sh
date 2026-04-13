#!/bin/bash

echo "=== MEDIKPEDIA VIDEO PLAYER VERIFICATION ==="
echo ""

# Check storage folder
echo "📁 Checking storage folders..."
if [ -d "storage/app/public/news" ]; then
    echo "✓ storage/app/public/news folder exists"
    echo "  Files:"
    ls -la storage/app/public/news/ | tail -n +4 || echo "  (Empty)"
else
    echo "✗ storage/app/public/news folder NOT found"
fi

echo ""
echo "🔗 Checking public/storage symlink..."
if [ -L "public/storage" ]; then
    echo "✓ Symlink exists"
    link_target=$(readlink -f public/storage)
    echo "  Points to: $link_target"
else
    echo "✗ Symlink NOT found"
fi

echo ""
echo "📰 Checking news in database..."
php artisan tinker << 'EOF'
$videos = \App\Models\News::where('tipe', 'video')->get(['id', 'judul', 'file', 'thumbnail']);
if ($videos->count() > 0) {
    echo "Found " . $videos->count() . " video news:\n";
    foreach ($videos as $news) {
        echo "  • " . $news->judul . "\n";
        echo "    File: " . ($news->file ? $news->file : "NONE") . "\n";
        echo "    Thumb: " . ($news->thumbnail ? $news->thumbnail : "NONE") . "\n";
    }
} else {
    echo "No video news found.\n";
}
EOF

echo ""
echo "=== RECOMMENDATIONS ==="
echo ""
echo "If videos not playing:"
echo "1. Check that video files exist in storage/app/public/news/"
echo "2. Verify public/storage symlink points correctly"
echo "3. Try running: php artisan storage:link"
echo "4. Check browser console for CORS or 404 errors"
echo "5. Supported formats: MP4, WebM, MOV, AVI, MKV"
echo ""
