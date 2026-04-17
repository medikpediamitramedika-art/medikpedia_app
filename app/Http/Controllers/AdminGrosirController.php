<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Constants\Companies;
use Illuminate\Support\Facades\Storage;

class AdminGrosirController extends Controller
{
    private array $companies = Companies::LIST;

    // List produk grosir
    public function index(Request $request)
    {
        $search   = $request->get('search', '');
        $kategori = $request->get('kategori', '');

        $query = Medicine::where('is_grosir', true)->latest();

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

        $medicines   = $query->paginate(10)->withQueryString();
        $perusahaans = Companies::LIST;
        $categories  = Companies::LIST;
        $total       = Medicine::where('is_grosir', true)->count();

        return view('admin.grosir.index', compact(
            'medicines', 'search', 'kategori', 'perusahaans', 'categories', 'total'
        ));
    }

    // Form tambah produk grosir
    public function create()
    {
        return view('admin.grosir.create', ['categories' => $this->companies]);
    }

    // Simpan produk grosir baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => ['required', 'string', 'max:255'],
            'kategori'  => ['required', 'string'],
            'harga'     => ['required', 'numeric', 'min:0'],
            'stok'      => ['required', 'integer', 'min:0'],
            'komposisi' => ['required', 'string', 'max:255'],
            'indikasi'  => ['required', 'string', 'max:255'],
            'golongan'  => ['required', 'in:BEBAS,KERAS'],
            'gambar'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ]);

        // Produk grosir selalu is_grosir=true, is_resep=false
        $validated['is_grosir'] = true;
        $validated['is_resep']  = false;

        $validated['deskripsi'] = $validated['komposisi'] . ' | ' . $validated['indikasi'];
        unset($validated['komposisi'], $validated['indikasi'], $validated['golongan']);

        if ($request->hasFile('gambar')) {
            $image     = $request->file('gambar');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('medicines', $imageName, 'public');
            $validated['gambar'] = 'medicines/' . $imageName;
        }

        Medicine::create($validated);

        return redirect()->route('admin.grosir.index')
                         ->with('success', 'Produk grosir berhasil ditambahkan!');
    }

    // Form edit produk grosir
    public function edit(Medicine $grosir)
    {
        // Pastikan yang diedit memang produk grosir
        abort_if(!$grosir->is_grosir, 403, 'Bukan produk grosir.');

        return view('admin.grosir.edit', [
            'medicine'   => $grosir,
            'categories' => $this->companies,
        ]);
    }

    // Update produk grosir
    public function update(Request $request, Medicine $grosir)
    {
        abort_if(!$grosir->is_grosir, 403, 'Bukan produk grosir.');

        $validated = $request->validate([
            'nama_obat'     => ['required', 'string', 'max:255'],
            'kategori'      => ['required', 'string'],
            'harga'         => ['required', 'numeric', 'min:0'],
            'stok'          => ['required', 'integer', 'min:0'],
            'komposisi'     => ['required', 'string', 'max:255'],
            'indikasi'      => ['required', 'string', 'max:255'],
            'golongan'      => ['required', 'in:BEBAS,KERAS'],
            'gambar'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'delete_gambar' => ['nullable', 'boolean'],
        ]);

        // Selalu pastikan tetap grosir
        $validated['is_grosir'] = true;
        $validated['is_resep']  = false;

        $validated['deskripsi'] = $validated['komposisi'] . ' | ' . $validated['indikasi'];
        unset($validated['komposisi'], $validated['indikasi'], $validated['golongan'], $validated['delete_gambar']);

        if ($request->hasFile('gambar')) {
            if ($grosir->gambar) {
                Storage::disk('public')->delete($grosir->gambar);
            }
            $image     = $request->file('gambar');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('medicines', $imageName, 'public');
            $validated['gambar'] = 'medicines/' . $imageName;
        } elseif ($request->input('delete_gambar') == '1' && $grosir->gambar) {
            Storage::disk('public')->delete($grosir->gambar);
            $validated['gambar'] = null;
        }

        $grosir->update($validated);

        return redirect()->route('admin.grosir.index')
                         ->with('success', 'Produk grosir berhasil diupdate!');
    }

    // Hapus produk grosir
    public function destroy(Medicine $grosir)
    {
        abort_if(!$grosir->is_grosir, 403, 'Bukan produk grosir.');

        if ($grosir->gambar) {
            Storage::disk('public')->delete($grosir->gambar);
        }

        $grosir->delete();

        return redirect()->route('admin.grosir.index')
                         ->with('success', 'Produk grosir berhasil dihapus!');
    }

    // Update stok
    public function updateStock(Request $request, Medicine $grosir)
    {
        abort_if(!$grosir->is_grosir, 403, 'Bukan produk grosir.');

        $validated = $request->validate([
            'stok' => ['required', 'integer', 'min:0'],
        ]);

        $grosir->update(['stok' => $validated['stok']]);

        return back()->with('success', 'Stok berhasil diupdate!');
    }
}
