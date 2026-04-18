<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class ImageHelper
{
    /**
     * Simpan gambar produk langsung ke public_path/storage/medicines/
     * 
     * Karena document root di public_html/ (bukan public_html/public/),
     * maka public_path() = /home/user/public_html/
     * 
     * File tersimpan di: public_html/storage/medicines/namafile.jpg
     * URL akses: https://domain.com/storage/medicines/namafile.jpg
     * Nilai di DB: "medicines/namafile.jpg"
     */
    public static function storeProductImage(UploadedFile $file): string
    {
        $ext       = strtolower($file->getClientOriginalExtension());
        $imageName = time() . '_' . uniqid() . '.' . $ext;
        $targetDir = public_path('storage/medicines');

        // Pastikan folder ada
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Simpan langsung
        $file->move($targetDir, $imageName);

        return 'medicines/' . $imageName;
    }

    /**
     * Hapus gambar produk.
     * $path dari DB: "medicines/namafile.jpg"
     */
    public static function deleteProductImage(?string $path): void
    {
        if (!$path) return;

        $fullPath = public_path('storage/' . $path);
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
}
