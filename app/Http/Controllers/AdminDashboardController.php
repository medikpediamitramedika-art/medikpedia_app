<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\News;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Retail stats
        $totalMedicines   = Medicine::where('is_grosir', false)->count();
        $totalStok        = Medicine::where('is_grosir', false)->sum('stok');
        $lowStokMedicines = Medicine::where('is_grosir', false)->where('stok', '<', 5)->count();
        $medicines        = Medicine::where('is_grosir', false)->latest()->limit(10)->get();

        // Grosir stats
        $totalGrosir      = Medicine::where('is_grosir', true)->count();
        $totalStokGrosir  = Medicine::where('is_grosir', true)->sum('stok');
        $latestGrosir     = Medicine::where('is_grosir', true)->latest()->limit(10)->get();

        $categories = Medicine::distinct()->pluck('kategori');

        // Get latest news
        $latestNews    = News::latest()->limit(5)->get();
        $totalNews     = News::count();
        $publishedNews = News::where('is_published', true)->count();

        return view('admin.dashboard', [
            'totalMedicines'   => $totalMedicines,
            'totalStok'        => $totalStok,
            'lowStokMedicines' => $lowStokMedicines,
            'medicines'        => $medicines,
            'totalGrosir'      => $totalGrosir,
            'totalStokGrosir'  => $totalStokGrosir,
            'latestGrosir'     => $latestGrosir,
            'categories'       => $categories,
            'latestNews'       => $latestNews,
            'totalNews'        => $totalNews,
            'publishedNews'    => $publishedNews,
        ]);
    }

    public function stats()
    {
        $latestRetail = Medicine::where('is_grosir', false)->latest()->limit(10)
            ->get(['id','nama_obat','kategori','harga','stok','created_at'])
            ->map(fn($m) => [
                'id'         => $m->id,
                'nama_obat'  => $m->nama_obat,
                'kategori'   => $m->kategori,
                'harga'      => $m->harga,
                'stok'       => $m->stok,
                'created_at' => $m->created_at->format('d M Y H:i'),
            ]);

        $latestGrosir = Medicine::where('is_grosir', true)->latest()->limit(10)
            ->get(['id','nama_obat','kategori','harga','stok','created_at'])
            ->map(fn($m) => [
                'id'         => $m->id,
                'nama_obat'  => $m->nama_obat,
                'kategori'   => $m->kategori,
                'harga'      => $m->harga,
                'stok'       => $m->stok,
                'created_at' => $m->created_at->format('d M Y H:i'),
            ]);

        return response()->json([
            'retail'        => Medicine::where('is_grosir', false)->count(),
            'grosir'        => Medicine::where('is_grosir', true)->count(),
            'total'         => Medicine::count(),
            'lowStok'       => Medicine::where('is_grosir', false)->where('stok', '<', 5)->count(),
            'latestRetail'  => $latestRetail,
            'latestGrosir'  => $latestGrosir,
        ]);
    }
}
