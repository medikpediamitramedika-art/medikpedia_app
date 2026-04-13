<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMedicineController extends Controller
{
    private array $companies = Companies::LIST;
    // List obat
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $kategori = $request->input('kategori');

        $query = Medicine::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $medicines  = $query->paginate(10)->withQueryString();
        $categories = Companies::LIST;

        return view('admin.medicines.index', compact('medicines', 'search', 'kategori', 'categories'));
    }

    // Form tambah obat
    public function create()
    {
        return view('admin.medicines.create', ['categories' => $this->companies]);
    }

    // Simpan obat baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ]);

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/medicines', $imageName);
            $validated['gambar'] = 'medicines/' . $imageName;
        }

        Medicine::create($validated);

        return redirect()->route('admin.medicines.index')
                       ->with('success', 'Obat berhasil ditambahkan!');
    }

    // Form edit obat
    public function edit(Medicine $medicine)
    {
        return view('admin.medicines.edit', [
            'medicine'   => $medicine,
            'categories' => $this->companies,
        ]);
    }

    // Update obat
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ]);

        // Handle upload gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($medicine->gambar) {
                Storage::delete('public/' . $medicine->gambar);
            }

            // Upload gambar baru
            $image = $request->file('gambar');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/medicines', $imageName);
            $validated['gambar'] = 'medicines/' . $imageName;
        }

        $medicine->update($validated);

        return redirect()->route('admin.medicines.index')
                       ->with('success', 'Obat berhasil diupdate!');
    }

    // Hapus obat
    public function destroy(Medicine $medicine)
    {
        // Hapus gambar
        if ($medicine->gambar) {
            Storage::delete('public/' . $medicine->gambar);
        }

        $medicine->delete();

        return redirect()->route('admin.medicines.index')
                       ->with('success', 'Obat berhasil dihapus!');
    }

    // Update stok
    public function updateStock(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'stok' => ['required', 'integer', 'min:0'],
        ]);

        $medicine->update(['stok' => $validated['stok']]);

        return back()->with('success', 'Stok berhasil diupdate!');
    }
}
