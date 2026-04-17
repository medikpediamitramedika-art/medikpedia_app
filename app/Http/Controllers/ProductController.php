<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineGrosir; // 🔥 TAMBAHAN
use App\Constants\Companies;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function update(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        if ($request->hasFile('gambar')) {

            $oldPath = '/home/u656662250/domains/medikpedia.com/public_html/storage/' . $medicine->gambar;
            if ($medicine->gambar && file_exists($oldPath)) {
                unlink($oldPath);
            }

            $path = $request->file('gambar')->store('medicines', 'public');

            $medicine->update([
                'gambar' => $path
            ]);
        }

        return back()->with('success', 'Foto berhasil diperbarui!');
    }

    /**
     * =========================
     * RETAIL (existing)
     * =========================
     */
    public function index(Request $request)
    {
        $search     = $request->get('search', '');
        $perusahaan = $request->get('perusahaan', '');
        $sort       = $request->get('sort', 'terbaru');

        $query = Medicine::where('is_resep', false);

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
        $total       = Medicine::where('is_resep', false)->count();

        return view('products', compact(
            'medicines',
            'search',
            'perusahaan',
            'sort',
            'perusahaans',
            'total'
        ));
    }

    /**
     * =========================
     * GROSIR (🔥 BARU)
     * =========================
     */
    public function grosir(Request $request)
{
    $search     = $request->get('search', '');
    $perusahaan = $request->get('perusahaan', '');
    $sort       = $request->get('sort', 'terbaru');

    // 🔥 FILTER KHUSUS GROSIR
    $query = Medicine::where('is_grosir', true);

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
    $perusahaans = \App\Constants\Companies::LIST;
    $total       = Medicine::where('is_grosir', true)->count();

    return view('products_grosir', compact(
        'medicines',
        'search',
        'perusahaan',
        'sort',
        'perusahaans',
        'total'
    ));
}
}