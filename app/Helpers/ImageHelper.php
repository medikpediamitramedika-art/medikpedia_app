<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class ImageHelper
{
    /**
     * Simpan gambar produk langsung ke public/storage/medicines/
     * Tidak pakai symlink karena LiteSpeed server tidak support symlink.
     * 
     * File tersimpan di: public/storage/medicines/namafile.jpg
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

        // Simpan langsung ke public/storage/medicines/
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
