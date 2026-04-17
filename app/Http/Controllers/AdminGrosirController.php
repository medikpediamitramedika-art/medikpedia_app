<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Constants\Companies;

class AdminGrosirController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('search', '');
        $kategori = $request->get('kategori', '');

        $query = Medicine::where('is_grosir', true);

        // 🔍 SEARCH
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // 🏷 FILTER KATEGORI
        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        // 🔥 PAGINATION WAJIB
        $medicines = $query->latest()->paginate(10)->withQueryString();

        $perusahaans = Companies::LIST;
        $categories  = Companies::LIST; // 🔥 INI YANG KURANG
        $total       = $query->count();

        return view('admin.grosir.index', compact(
            'medicines',
            'search',
            'kategori',
            'perusahaans',
            'categories', // 🔥 TAMBAHKAN INI
            'total'
        ));
    }
}