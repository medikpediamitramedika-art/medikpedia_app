<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Simpan gambar produk.
     * 
     * Strategi: Simpan via Storage::disk('public') agar kompatibel dengan
     * symlink di server maupun lokal development.
     * 
     * File tersimpan di: storage/app/public/medicines/namafile.jpg
     * Akses via symlink: public/storage/medicines/namafile.jpg
     * URL: https://domain.com/storage/medicines/namafile.jpg
     * 
     * Nilai di DB: "medicines/namafile.jpg"
     */
    public static function storeProductImage(UploadedFile $file): string
    {
        $ext       = strtolower($file->getClientOriginalExtension());
        $imageName = time() . '_' . uniqid() . '.' . $ext;
        
        // Simpan via Storage disk 'public' (storage/app/public/)
        $path = $file->storeAs('medicines', $imageName, 'public');
        
        return $path; // "medicines/namafile.jpg"
    }

    /**
     * Hapus gambar produk.
     * $path dari DB: "medicines/namafile.jpg"
     */
    public static function deleteProductImage(?string $path): void
    {
        if (!$path) return;
        
        // Hapus via Storage disk
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Kembalikan URL gambar yang benar.
     * $path dari DB: "medicines/namafile.jpg"
     */
    public static function url(?string $path): ?string
    {
        if (!$path) return null;
        return url('storage/' . $path);
    }
}
