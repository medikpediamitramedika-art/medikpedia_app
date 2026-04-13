<?php

require_once 'bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Import models
use App\Models\News;
use App\Models\Medicine;
use App\Models\User;

echo "=== MEDIKPEDIA DATABASE VERIFICATION ===\n\n";

echo "📊 DATABASE STATISTICS:\n";
echo "- Total Users: " . User::count() . "\n";
echo "- Total Medicines: " . Medicine::count() . "\n";
echo "- Total News: " . News::count() . "\n\n";

echo "👥 ADMIN USERS:\n";
$admins = User::where('role', 'admin')->get();
foreach($admins as $admin) {
    echo "  • " . $admin->name . " (" . $admin->email . ")\n";
}

echo "\n📰 NEWS ARTICLES:\n";
$newsList = News::all();
foreach($newsList as $news) {
    echo "  • " . $news->judul . " [" . strtoupper($news->tipe) . "] - Views: " . $news->views . "\n";
}

echo "\n💊 SAMPLE MEDICINES (First 5):\n";
$medicines = Medicine::take(5)->get();
foreach($medicines as $med) {
    echo "  • " . $med->nama_obat . " - Rp" . number_format($med->harga, 0, ',', '.') . " (Stok: " . $med->stok . ")\n";
}

echo "\n✅ SETUP COMPLETE!\n";
echo "\n🌐 ACCESS POINTS:\n";
echo "  → Frontend: http://localhost:8000/\n";
echo "  → News List: http://localhost:8000/berita\n";
echo "  → About Page: http://localhost:8000/tentang-kami\n";
echo "  → Admin Login: http://localhost:8000/admin/login\n";
echo "  → Admin News: http://localhost:8000/admin/news\n";
echo "  → Admin Medicines: http://localhost:8000/admin/medicines\n";
?>
