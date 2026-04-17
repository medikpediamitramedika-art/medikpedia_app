<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineGrosir extends Model
{
    protected $table = 'medicines_grosir';

    protected $fillable = [
        'nama_obat',
        'kategori',
        'harga',
        'stok',
        'deskripsi',
        'is_resep'
    ];
}