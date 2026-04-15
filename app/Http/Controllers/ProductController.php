<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function update(Request $request, $id)
{
    $medicine = Medicine::findOrFail($id);

    if ($request->hasFile('gambar')) {
        // Hapus foto lama jika ada
        if ($medicine->gambar && file_exists(storage_path('app/public/' . $medicine->gambar))) {
            unlink(storage_path('app/public/' . $medicine->gambar));
        }

        // Upload foto baru ke folder 'medicines' di disk 'public'
        // Ini akan otomatis masuk ke storage/app/public/medicines
        $path = $request->file('gambar')->store('medicines', 'public');
        
        $medicine->update([
            'gambar' => $path
        ]);
    }

    return back()->with('success', 'Foto berhasil diperbarui!');
}
    public function index(Request $request)
    {
        $search     = $request->get('search', '');
        $perusahaan = $request->get('perusahaan', '');
        $sort       = $request->get('sort', 'terbaru');

        $query = Medicine::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($perusahaan) {
            $query->where('kategori', $perusahaan);
        }

        match ($sort) {
            'harga_asc'  => $query->orderBy('harga', 'asc'),
            'harga_desc' => $query->orderBy('harga', 'desc'),
            'nama'       => $query->orderBy('nama_obat', 'asc'),
            default      => $query->latest(),
        };

        $medicines   = $query->paginate(12)->withQueryString();
        $perusahaans = Companies::LIST;
        $total       = Medicine::count();

        return view('products', compact('medicines', 'search', 'perusahaan', 'sort', 'perusahaans', 'total'));
    }
}
