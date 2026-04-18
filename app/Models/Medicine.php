<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;

    protected $table = 'medicines';

    protected $fillable = [
        'nama_obat',
        'kategori',
        'kategori_produk',
        'harga',
        'stok',
        'deskripsi',
        'komposisi',
        'indikasi',
        'gambar',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok'  => 'integer',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('nama_obat', 'like', "%{$search}%")
                     ->orWhere('kategori', 'like', "%{$search}%")
                     ->orWhere('deskripsi', 'like', "%{$search}%");
    }

    public function scopeByCategory($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function isAvailable(): bool
    {
        return $this->stok > 0;
    }

    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Get full URL gambar produk
     * Gunakan: $medicine->image_url di blade
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }
        
        // Coba beberapa kemungkinan path
        $paths = [
            'storage/' . $this->gambar,           // public/storage/medicines/xxx.jpg
            $this->gambar,                         // langsung medicines/xxx.jpg
            'public/storage/' . $this->gambar,    // public/public/storage/medicines/xxx.jpg (hosting aneh)
        ];
        
        foreach ($paths as $path) {
            if (file_exists(public_path($path))) {
                return url($path);
            }
        }
        
        // Fallback: return URL tanpa cek file
        return url('storage/' . $this->gambar);
    }
}
