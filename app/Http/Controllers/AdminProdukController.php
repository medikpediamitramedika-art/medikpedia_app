<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Constants\Companies;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Storage;

class AdminProdukController extends Controller
{
    private array $kategoriProduk = Companies::LIST;

    public function index(Request $request)
    {
        $search          = $request->get('search', '');
        $kategori_produk = $request->get('kategori_produk', '');
        $pabrik          = $request->get('pabrik', '');

        $query = Medicine::latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($kategori_produk) {
            $query->where('kategori_produk', $kategori_produk);
        }

        if ($pabrik) {
            $query->where('kategori', $pabrik);
        }

        $medicines       = $query->paginate(15)->withQueryString();
        $total           = Medicine::count();
        $kategoriOptions = Companies::LIST;

        return view('admin.produk.index', compact(
            'medicines', 'search', 'kategori_produk', 'pabrik', 'total', 'kategoriOptions'
        ));
    }

    public function create()
    {
        return view('admin.produk.create', [
            'kategoriOptions' => $this->kategoriProduk,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat'       => ['required', 'string', 'max:255'],
            'kategori_produk' => ['required', 'in:' . implode(',', Companies::LIST)],
            'kategori'        => ['required', 'string', 'max:255'],
            'harga'           => ['required', 'numeric', 'min:0'],
            'stok'            => ['required', 'integer', 'min:0'],
            'komposisi'       => ['nullable', 'string'],
            'indikasi'        => ['nullable', 'string'],
            'gambar'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = ImageHelper::storeProductImage($request->file('gambar'));
        }

        // deskripsi wajib NOT NULL di DB lama — isi string kosong jika tidak ada
        $validated['deskripsi'] = '';

        Medicine::create($validated);

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Medicine $produk)
    {
        return view('admin.produk.edit', [
            'medicine'        => $produk,
            'kategoriOptions' => $this->kategoriProduk,
        ]);
    }

    public function update(Request $request, Medicine $produk)
    {
        $validated = $request->validate([
            'nama_obat'       => ['required', 'string', 'max:255'],
            'kategori_produk' => ['required', 'in:' . implode(',', Companies::LIST)],
            'kategori'        => ['required', 'string', 'max:255'],
            'harga'           => ['required', 'numeric', 'min:0'],
            'stok'            => ['required', 'integer', 'min:0'],
            'komposisi'       => ['nullable', 'string'],
            'indikasi'        => ['nullable', 'string'],
            'gambar'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'delete_gambar'   => ['nullable'],
        ]);

        unset($validated['delete_gambar']);

        if ($request->hasFile('gambar')) {
            ImageHelper::deleteProductImage($produk->gambar);
            $validated['gambar'] = ImageHelper::storeProductImage($request->file('gambar'));
        } elseif ($request->input('delete_gambar') == '1' && $produk->gambar) {
            ImageHelper::deleteProductImage($produk->gambar);
            $validated['gambar'] = null;
        }

        $produk->update($validated);

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(Medicine $produk)
    {
        ImageHelper::deleteProductImage($produk->gambar);
        $produk->delete();

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }

    public function updateStock(Request $request, Medicine $produk)
    {
        $validated = $request->validate(['stok' => ['required', 'integer', 'min:0']]);
        $produk->update(['stok' => $validated['stok']]);
        return back()->with('success', 'Stok berhasil diupdate!');
    }

    public function show(Medicine $produk)
    {
        return redirect()->route('admin.produk.index');
    }
}
