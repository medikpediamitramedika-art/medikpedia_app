<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\News;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalMedicines = Medicine::count();
        $totalStok = Medicine::sum('stok');
        $lowStokMedicines = Medicine::where('stok', '<', 5)->count();
        
        $medicines = Medicine::latest()->limit(10)->get();
        $categories = Medicine::distinct()->pluck('kategori');
        
        // Get latest news
        $latestNews = News::latest()->limit(5)->get();
        $totalNews = News::count();
        $publishedNews = News::where('is_published', true)->count();

        return view('admin.dashboard', [
            'totalMedicines' => $totalMedicines,
            'totalStok' => $totalStok,
            'lowStokMedicines' => $lowStokMedicines,
            'medicines' => $medicines,
            'categories' => $categories,
            'latestNews' => $latestNews,
            'totalNews' => $totalNews,
            'publishedNews' => $publishedNews,
        ]);
    }
}
