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
        'harga',
        'stok',
        'deskripsi',
        'gambar',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
    ];

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_obat', 'like', "%{$search}%")
                     ->orWhere('kategori', 'like', "%{$search}%")
                     ->orWhere('deskripsi', 'like', "%{$search}%");
    }

    // Scope untuk kategori
    public function scopeByCategory($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Cek stok
    public function isAvailable()
    {
        return $this->stok > 0;
    }

    // Format harga
    public function getFormattedPrice()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
