<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\News;
use App\Constants\Companies;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalProduk    = Medicine::count();
        $totalStok      = Medicine::sum('stok');
        $lowStok        = Medicine::where('stok', '<', 5)->count();
        $latestProduk   = Medicine::latest()->limit(10)->get();

        // Per kategori produk
        $perKategori = [];
        foreach (Companies::LIST as $kat) {
            $perKategori[$kat] = Medicine::where('kategori_produk', $kat)->count();
        }

        $latestNews    = News::latest()->limit(5)->get();
        $totalNews     = News::count();
        $publishedNews = News::where('is_published', true)->count();

        return view('admin.dashboard', compact(
            'totalProduk', 'totalStok', 'lowStok', 'latestProduk',
            'perKategori', 'latestNews', 'totalNews', 'publishedNews'
        ));
    }

    public function stats()
    {
        $latestProduk = Medicine::latest()->limit(10)
            ->get(['id', 'nama_obat', 'kategori', 'kategori_produk', 'harga', 'stok', 'created_at'])
            ->map(fn($m) => [
                'id'              => $m->id,
                'nama_obat'       => $m->nama_obat,
                'kategori'        => $m->kategori,
                'kategori_produk' => $m->kategori_produk,
                'harga'           => $m->harga,
                'stok'            => $m->stok,
                'created_at'      => $m->created_at->format('d M Y H:i'),
            ]);

        return response()->json([
            'total'         => Medicine::count(),
            'lowStok'       => Medicine::where('stok', '<', 5)->count(),
            'latestProduk'  => $latestProduk,
        ]);
    }
}
