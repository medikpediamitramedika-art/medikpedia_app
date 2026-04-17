<?php
// Skrip untuk membuat symbolic link secara manual
$targetFolder = __DIR__ . '/storage/app/public';
$linkFolder = __DIR__ . '/storage'; // Ini akan membuat folder 'storage' di dalam public_html

if (file_exists($linkFolder)) {
    echo "Folder link 'storage' sudah ada. Kita hapus dulu...<br>";
    // Jika itu link, kita hapus
    unlink($linkFolder);
}

if (symlink($targetFolder, $linkFolder)) {
    echo "<strong>Berhasil!</strong> Storage link sudah terhubung.<br>";
    echo "Sekarang gambar harusnya bisa dipanggil via: asset('storage/nama_file.jpg')";
} else {
    echo "<strong>Gagal!</strong> Fungsi symlink() kemungkinan dinonaktifkan oleh hosting.";
}