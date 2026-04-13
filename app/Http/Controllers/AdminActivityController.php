<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminActivityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query  = Activity::latest();

        if ($search) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        $activities = $query->paginate(12)->withQueryString();

        return view('admin.activities.index', compact('activities', 'search'));
    }

    public function create()
    {
        return view('admin.activities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'deskripsi'    => ['nullable', 'string'],
            'foto'         => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'tanggal'      => ['required', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $foto     = $request->file('foto');
        $fotoName = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
        $foto->storeAs('activities', $fotoName, 'public');

        Activity::create([
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'foto'         => 'activities/' . $fotoName,
            'tanggal'      => $request->tanggal,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.activities.index')
                         ->with('success', 'Aktivitas berhasil ditambahkan!');
    }

    public function edit(Activity $activity)
    {
        return view('admin.activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'judul'        => ['required', 'string', 'max:255'],
            'deskripsi'    => ['nullable', 'string'],
            'foto'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'tanggal'      => ['required', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data = [
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'tanggal'      => $request->tanggal,
            'is_published' => $request->has('is_published'),
        ];

        if ($request->hasFile('foto')) {
            Storage::disk('public')->delete($activity->foto);
            $foto     = $request->file('foto');
            $fotoName = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('activities', $fotoName, 'public');
            $data['foto'] = 'activities/' . $fotoName;
        }

        $activity->update($data);

        return redirect()->route('admin.activities.index')
                         ->with('success', 'Aktivitas berhasil diupdate!');
    }

    public function destroy(Activity $activity)
    {
        Storage::disk('public')->delete($activity->foto);
        $activity->delete();

        return redirect()->route('admin.activities.index')
                         ->with('success', 'Aktivitas berhasil dihapus!');
    }
}
