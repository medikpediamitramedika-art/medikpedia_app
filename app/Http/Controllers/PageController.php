<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        $latestNews = News::latest()->take(3)->get();
        return view('pages.home', compact('latestNews'));
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function products(): View
    {
        return view('pages.products');
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}
