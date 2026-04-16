<?php
/**
 * Script untuk cek apakah file controller sudah terupdate di server
 * Upload file ini ke root project, lalu akses via browser:
 * https://your-domain.com/check-update.php
 * 
 * HAPUS FILE INI setelah selesai testing!
 */

echo "<h1>🔍 Check File Update Status</h1>";
echo "<style>body{font-family:sans-serif;padding:20px;} .ok{color:green;} .error{color:red;} pre{background:#f5f5f5;padding:10px;border-radius:5px;}</style>";

$files = [
    'app/Http/Controllers/AdminMedicineController.php',
    'app/Http/Controllers/AdminPrescriptionProductController.php',
    'resources/views/admin/medicines/edit.blade.php',
    'resources/views/admin/prescriptions/products/edit.blade.php',
];

echo "<h2>📁 File Status:</h2>";

foreach ($files as $file) {
    $fullPath = __DIR__ . '/' . $file;
    
    if (file_exists($fullPath)) {
        $modTime = filemtime($fullPath);
        $modDate = date('Y-m-d H:i:s', $modTime);
        $size = filesize($fullPath);
        
        echo "<div style='margin-bottom:15px;padding:10px;border:1px solid #ddd;border-radius:5px;'>";
        echo "<strong class='ok'>✅ {$file}</strong><br>";
        echo "Last Modified: <strong>{$modDate}</strong><br>";
        echo "Size: " . number_format($size) . " bytes<br>";
        
        // Check for specific code
        $content = file_get_contents($fullPath);
        
        if (strpos($file, 'Controller.php') !== false) {
            if (strpos($content, "'delete_gambar' => ['nullable', 'boolean']") !== false) {
                echo "<span class='ok'>✅ Contains delete_gambar validation</span><br>";
            } else {
                echo "<span class='error'>❌ Missing delete_gambar validation</span><br>";
            }
            
            if (strpos($content, "->input('delete_gambar') == '1'") !== false) {
                echo "<span class='ok'>✅ Contains updated delete logic</span><br>";
            } else {
                echo "<span class='error'>❌ Missing updated delete logic</span><br>";
            }
        }
        
        echo "</div>";
    } else {
        echo "<div style='margin-bottom:15px;padding:10px;border:1px solid #f00;border-radius:5px;'>";
        echo "<strong class='error'>❌ {$file}</strong><br>";
        echo "File not found!<br>";
        echo "</div>";
    }
}

echo "<hr>";
echo "<h2>🔧 Laravel Info:</h2>";
echo "<pre>";
echo "PHP Version: " . phpversion() . "\n";
echo "Laravel Path: " . __DIR__ . "\n";
echo "Current Time: " . date('Y-m-d H:i:s') . "\n";
echo "</pre>";

echo "<hr>";
echo "<h2>⚠️ Next Steps:</h2>";
echo "<ol>";
echo "<li>Jika file sudah terupdate tapi masih error, jalankan: <code>php artisan route:clear</code></li>";
echo "<li>Restart PHP-FPM: <code>sudo systemctl restart php-fpm</code></li>";
echo "<li>Clear browser cache: <code>Ctrl + Shift + R</code></li>";
echo "<li><strong>HAPUS file check-update.php ini setelah selesai!</strong></li>";
echo "</ol>";

echo "<hr>";
echo "<p style='color:#999;font-size:12px;'>Generated at: " . date('Y-m-d H:i:s') . "</p>";
?>
