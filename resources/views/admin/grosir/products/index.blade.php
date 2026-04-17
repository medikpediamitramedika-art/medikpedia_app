@extends('layouts.admin')

@section('title', 'Produk Resep - Admin Medikpedia')
@section('page-title', 'Manajemen Produk Resep')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h2 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #1f2937;">
            <i class="fa-solid fa-prescription-bottle"></i> Produk Resep
        </h2>
        <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #6b7280;">
            Kelola semua produk obat resep (KERAS)
        </p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="{{ route('admin.prescriptions.products.create') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </a>
        <a href="{{ route('admin.prescriptions.products.import') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-file-import"></i> Import Excel
        </a>
    </div>
</div>

{{-- Search & Filter --}}
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
        <div>
            <label class="form-label">Cari Produk</label>
            <input type="text" name="search" class="form-control" placeholder="Nama produk, pabrik..." value="{{ $search ?? '' }}">
        </div>
        <div>
            <label class="form-label">Pabrik</label>
            <select name="kategori" class="form-control">
                <option value="">-- Semua Pabrik --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ ($kategori ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div></div>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-search"></i> Cari
        </button>
    </form>
</div>

{{-- Messages --}}
@if($message = session('success'))
    <div style="background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
        <i class="fa-solid fa-check-circle"></i> {{ $message }}
    </div>
@endif

{{-- Table --}}
<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="border-bottom: 2px solid #e5e7eb; background: #f9fafb;">
                <th style="padding: 1rem; text-align: left; font-weight: 700; color: #374151; font-size: 0.875rem;">Nama Produk</th>
                <th style="padding: 1rem; text-align: left; font-weight: 700; color: #374151; font-size: 0.875rem;">Pabrik</th>
                <th style="padding: 1rem; text-align: right; font-weight: 700; color: #374151; font-size: 0.875rem;">Harga</th>
                <th style="padding: 1rem; text-align: right; font-weight: 700; color: #374151; font-size: 0.875rem;">Stok</th>
                <th style="padding: 1rem; text-align: center; font-weight: 700; color: #374151; font-size: 0.875rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medicines as $medicine)
                <tr style="border-bottom: 1px solid #e5e7eb; hover: background #f9fafb;">
                    <td style="padding: 1rem;">
                        <div style="font-weight: 600; color: #1f2937;">{{ $medicine->nama_obat }}</div>
                        <div style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">{{ Str::limit($medicine->deskripsi, 50) }}</div>
                    </td>
                    <td style="padding: 1rem; color: #6b7280;">{{ $medicine->kategori }}</td>
                    <td style="padding: 1rem; text-align: right; color: #1f2937; font-weight: 600;">Rp {{ number_format($medicine->harga, 0, ',', '.') }}</td>
                    <td style="padding: 1rem; text-align: right;">
                        <span style="display: inline-block; padding: 0.25rem 0.75rem; background: {{ $medicine->stok > 0 ? '#d1fae5' : '#fee2e2' }}; color: {{ $medicine->stok > 0 ? '#065f46' : '#991b1b' }}; border-radius: 0.25rem; font-size: 0.875rem; font-weight: 600;">
                            {{ $medicine->stok }}
                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('admin.prescriptions.products.edit', $medicine->id) }}" class="btn btn-sm btn-secondary" title="Edit">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.prescriptions.products.destroy', $medicine->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 2rem; text-align: center; color: #6b7280;">
                        <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                        Tidak ada produk resep
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($medicines->hasPages())
    <div style="margin-top: 1.5rem;">
        {{ $medicines->links() }}
    </div>
@endif
@endsection
