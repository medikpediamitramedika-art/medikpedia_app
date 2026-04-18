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
}
