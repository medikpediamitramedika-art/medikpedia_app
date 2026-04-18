<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Simpan gambar produk.
     * - Di hosting (public_html = root): simpan langsung ke public_html/storage/medicines/
     * - Di lokal: simpan via Storage::disk('public') ke storage/app/public/medicines/
     *
     * Nilai yang disimpan di DB selalu: "medicines/namafile.jpg"
     * URL di blade selalu: asset('storage/medicines/namafile.jpg')
     */
    public static function storeProductImage(UploadedFile $file): string
    {
        $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $subPath   = 'medicines/' . $imageName;

        if (self::isHosting()) {
            // Hosting: simpan langsung ke public_html/storage/medicines/
            $targetDir = public_path('storage/medicines');
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0775, true);
            }
            $file->move($targetDir, $imageName);
        } else {
            // Lokal: simpan via Laravel Storage (butuh symlink)
            $file->storeAs('medicines', $imageName, 'public');
        }

        return $subPath; // "medicines/namafile.jpg"
    }

    /**
     * Hapus gambar produk.
     * Nilai $path dari DB: "medicines/namafile.jpg"
     */
    public static function deleteProductImage(?string $path): void
    {
        if (!$path) return;

        if (self::isHosting()) {
            $fullPath = public_path('storage/' . $path);
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        } else {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Deteksi apakah berjalan di hosting (vendor ada di root yang sama dengan index.php)
     */
    private static function isHosting(): bool
    {
        return file_exists(base_path('vendor/autoload.php'))
            && file_exists(base_path('index.php'));
    }
}
