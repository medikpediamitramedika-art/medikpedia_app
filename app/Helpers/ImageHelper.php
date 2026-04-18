<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Simpan gambar produk langsung ke public/storage/medicines/
     *
     * Di server hosting: public_path() = public_html/public/
     * Jadi file tersimpan di: public_html/public/storage/medicines/namafile.jpg
     * URL akses: https://domain.com/storage/medicines/namafile.jpg
     *
     * Nilai yang disimpan di DB: "medicines/namafile.jpg"
     * URL di blade: asset('storage/medicines/namafile.jpg')
     */
    public static function storeProductImage(UploadedFile $file): string
    {
        $ext       = strtolower($file->getClientOriginalExtension());
        $imageName = time() . '_' . uniqid() . '.' . $ext;
        $targetDir = public_path('storage/medicines');

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

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

    /**
     * Kembalikan URL gambar yang benar tanpa bergantung pada APP_URL.
     * Gunakan ini di blade sebagai pengganti asset('storage/'.$medicine->gambar)
     * jika APP_URL di server belum dikonfigurasi.
     *
     * $path dari DB: "medicines/namafile.jpg"
     */
    public static function url(?string $path): ?string
    {
        if (!$path) return null;
        return url('storage/' . $path);
    }
}
