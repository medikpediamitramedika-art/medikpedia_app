<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminNewsController extends Controller
{
    // List berita
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tipe   = $request->input('tipe');
        $status = $request->input('status');

        $query = News::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($tipe) {
            $query->where('tipe', $tipe);
        }

        if ($status === 'published') {
            $query->where('is_published', true);
        } elseif ($status === 'draft') {
            $query->where('is_published', false);
        }

        $news = $query->paginate(10)->withQueryString();

        return view('admin.news.index', compact('news', 'search', 'tipe', 'status'));
    }

    // Form tambah berita
    public function create()
    {
        $types = [
            'diskon'        => 'Diskon',
            'flash_sale'    => 'Flash Sale',
            'bundling'      => 'Bundling',
            'promo_spesial' => 'Promo Spesial',
        ];
        return view('admin.news.create', ['types' => $types]);
    }

    // Simpan berita baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:500'],
            'konten' => ['required', 'string'],
            'tipe' => ['required', 'in:diskon,flash_sale,bundling,promo_spesial'],
            'file' => ['nullable', 'mimes:jpeg,png,jpg,gif,mp4,webm,mov,avi,mkv', 'max:20480'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['is_published'] = $request->has('is_published');

        // Handle upload file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            if (!$file->isValid()) {
                return redirect()->back()->with('error', 'File upload error: ' . $file->getErrorMessage());
            }
            
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('news', $fileName, 'public');
            
            if (!$path) {
                return redirect()->back()->with('error', 'Failed to store file to disk');
            }
            
            $validated['file'] = 'news/' . $fileName;
        }

        // Handle upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            
            if (!$thumb->isValid()) {
                return redirect()->back()->with('error', 'Thumbnail upload error: ' . $thumb->getErrorMessage());
            }
            
            $thumbName = 'thumb_' . time() . '_' . uniqid() . '.' . $thumb->getClientOriginalExtension();
            $thumbPath = $thumb->storeAs('news', $thumbName, 'public');
            
            if (!$thumbPath) {
                return redirect()->back()->with('error', 'Failed to store thumbnail to disk');
            }
            
            $validated['thumbnail'] = 'news/' . $thumbName;
        }

        News::create($validated);

        return redirect()->route('admin.news.index')
                       ->with('success', 'Produk Promo berhasil ditambahkan!');
    }

    // Form edit berita
    public function edit(News $news)
    {
        $types = [
            'diskon'        => 'Diskon',
            'flash_sale'    => 'Flash Sale',
            'bundling'      => 'Bundling',
            'promo_spesial' => 'Promo Spesial',
        ];
        return view('admin.news.edit', [
            'news' => $news,
            'types' => $types
        ]);
    }

    // Update berita
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:500'],
            'konten' => ['required', 'string'],
            'tipe' => ['required', 'in:diskon,flash_sale,bundling,promo_spesial'],
            'file' => ['nullable', 'mimes:jpeg,png,jpg,gif,mp4,webm,mov,avi,mkv', 'max:20480'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['is_published'] = $request->has('is_published');

        // Handle upload file baru
        if ($request->hasFile('file')) {
            if ($news->file) {
                Storage::disk('public')->delete($news->file);
            }
            $file = $request->file('file');
            
            if (!$file->isValid()) {
                return redirect()->back()->with('error', 'File upload error: ' . $file->getErrorMessage());
            }
            
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('news', $fileName, 'public');
            
            if (!$path) {
                return redirect()->back()->with('error', 'Failed to store file to disk');
            }
            
            $validated['file'] = 'news/' . $fileName;
        }

        // Handle upload thumbnail baru
        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $thumb = $request->file('thumbnail');
            
            if (!$thumb->isValid()) {
                return redirect()->back()->with('error', 'Thumbnail upload error: ' . $thumb->getErrorMessage());
            }
            
            $thumbName = 'thumb_' . time() . '_' . uniqid() . '.' . $thumb->getClientOriginalExtension();
            $thumbPath = $thumb->storeAs('news', $thumbName, 'public');
            
            if (!$thumbPath) {
                return redirect()->back()->with('error', 'Failed to store thumbnail to disk');
            }
            
            $validated['thumbnail'] = 'news/' . $thumbName;
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')
                       ->with('success', 'Produk Promo berhasil diupdate!');
    }

    // Hapus berita
    public function destroy(News $news)
    {
        if ($news->file) {
            Storage::disk('public')->delete($news->file);
        }
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }
        $news->delete();

        return redirect()->route('admin.news.index')
                       ->with('success', 'Produk Promo berhasil dihapus!');
    }
}
