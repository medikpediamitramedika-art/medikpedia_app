<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    // List berita di frontend
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $type = $request->get('type', '');

        $query = News::published();

        if ($search) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        if ($type) {
            $query->where('tipe', $type);
        }

        $news = $query->latest()->paginate(12);

        return view('news.index', [
            'news' => $news,
            'search' => $search,
            'type' => $type,
        ]);
    }

    // Detail berita
    public function show($id)
    {
        $news = News::findOrFail($id);
        $news->incrementViews();

        $relatedNews = News::published()
                          ->where('tipe', $news->tipe)
                          ->where('id', '!=', $news->id)
                          ->limit(3)
                          ->get();

        return view('news.detail', [
            'news' => $news,
            'relatedNews' => $relatedNews,
        ]);
    }

    // About toko
    public function about()
    {
        $latestNews = News::published()->latest()->limit(3)->get();
        return view('about', ['latestNews' => $latestNews]);
    }
}
