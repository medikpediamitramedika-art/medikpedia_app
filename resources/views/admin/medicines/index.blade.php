@extends('layouts.admin')

@section('title', 'Manajemen Obat - Admin Medikpedia')
@section('page-title', '💊 Manajemen Obat')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <p style="color: #6b7280;">Total: <strong>{{ $medicines->total() }} obat</strong>
            @if($search || $kategori)
                <span style="color: #1d4ed8;"> — hasil pencarian</span>
            @endif
        </p>
    </div>
    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <a href="{{ route('admin.medicines.import') }}" class="btn btn-secondary">
            📥 Import Excel/CSV
        </a>
        <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">
            ➕ Tambah Obat Baru
        </a>
    </div>
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('admin.medicines.index') }}"
      style="background: white; padding: 1rem 1.25rem; border-radius: 0.75rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">

    <div style="flex: 1; min-width: 200px;">
        <label style="font-size: 0.8rem; color: #6b7280; display: block; margin-bottom: 0.3rem;">Cari Obat</label>
        <div style="position: relative;">
            <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #9ca3af;">🔍</span>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Nama obat, kategori, deskripsi..."
                   style="width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9rem; outline: none;"
                   onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
        </div>
    </div>

    <div style="min-width: 160px;">
        <label style="font-size: 0.8rem; color: #6b7280; display: block; margin-bottom: 0.3rem;">Perusahaan</label>
        <select name="kategori"
                style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-size: 0.9rem; background: white; outline: none;"
                onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
            <option value="">Semua Perusahaan</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ $kategori === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>

    <div style="display: flex; gap: 0.5rem;">
        <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem;">
            Cari
        </button>
        @if($search || $kategori)
            <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                ✕ Reset
            </a>
        @endif
    </div>
</form>

@if($medicines->count() > 0)
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Obat</th>
                    <th>Perusahaan</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($medicines as $medicine)
                    <tr>
                        <td style="width: 50px;">
                            @if($medicine->gambar)
                                <img src="{{ asset('storage/' . $medicine->gambar) }}" 
                                     alt="{{ $medicine->nama_obat }}" 
                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 0.25rem;">
                            @else
                                <div style="width: 40px; height: 40px; background: #f3f4f6; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    💊
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $medicine->nama_obat }}</strong>
                        </td>
                        <td>{{ $medicine->kategori }}</td>
                        <td>{{ $medicine->getFormattedPrice() }}</td>
                        <td>
                            @if($medicine->stok > 10)
                                <span style="background: #d1fae5; color: #065f46; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">
                                    {{ $medicine->stok }}
                                </span>
                            @elseif($medicine->stok > 0)
                                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">
                                    {{ $medicine->stok }}
                                </span>
                            @else
                                <span style="background: #fee2e2; color: #7f1d1d; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">
                                    {{ $medicine->stok }}
                                </span>
                            @endif
                        </td>
                        <td>{{ $medicine->created_at->format('d M Y') }}</td>
                        <td style="width: 200px;">
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.medicines.edit', $medicine->id) }}" class="btn btn-secondary btn-sm">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.medicines.destroy', $medicine->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="color: #6b7280; font-size: 0.875rem;">
            Menampilkan {{ $medicines->firstItem() }}–{{ $medicines->lastItem() }} dari {{ $medicines->total() }} obat
        </div>
        <div style="display: flex; gap: 0.35rem; align-items: center;">
            {{-- Prev --}}
            @if($medicines->onFirstPage())
                <span style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: #f3f4f6; color: #d1d5db; font-size: 0.875rem; cursor: not-allowed;">‹</span>
            @else
                <a href="{{ $medicines->previousPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: white; color: #374151; font-size: 0.875rem; text-decoration: none; border: 1px solid #e5e7eb; transition: all 0.2s;" onmouseover="this.style.background='#1E88E5';this.style.color='white'" onmouseout="this.style.background='white';this.style.color='#374151'">‹</a>
            @endif

            {{-- Page Numbers --}}
            @foreach($medicines->getUrlRange(1, $medicines->lastPage()) as $page => $url)
                @if($page == $medicines->currentPage())
                    <span style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: #1E88E5; color: white; font-size: 0.875rem; font-weight: 600; min-width: 36px; text-align: center;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: white; color: #374151; font-size: 0.875rem; text-decoration: none; border: 1px solid #e5e7eb; min-width: 36px; text-align: center; transition: all 0.2s;" onmouseover="this.style.background='#1E88E5';this.style.color='white'" onmouseout="this.style.background='white';this.style.color='#374151'">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if($medicines->hasMorePages())
                <a href="{{ $medicines->nextPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: white; color: #374151; font-size: 0.875rem; text-decoration: none; border: 1px solid #e5e7eb; transition: all 0.2s;" onmouseover="this.style.background='#1E88E5';this.style.color='white'" onmouseout="this.style.background='white';this.style.color='#374151'">›</a>
            @else
                <span style="padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: #f3f4f6; color: #d1d5db; font-size: 0.875rem; cursor: not-allowed;">›</span>
            @endif
        </div>
    </div>
@else
    <div style="background: white; padding: 3rem; border-radius: 0.75rem; text-align: center; color: #6b7280;">
        @if($search || $kategori)
            <div style="font-size: 2rem; margin-bottom: 1rem;">🔍</div>
            <p>Tidak ada obat yang cocok dengan pencarian <strong>"{{ $search ?: $kategori }}"</strong>.</p>
            <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary" style="margin-top: 1rem;">
                ✕ Hapus Filter
            </a>
        @else
            <div style="font-size: 2rem; margin-bottom: 1rem;">📦</div>
            <p>Belum ada obat.</p>
            <div style="display: flex; gap: 0.75rem; justify-content: center; margin-top: 1rem; flex-wrap: wrap;">
                <a href="{{ route('admin.medicines.import') }}" class="btn btn-secondary">
                    📥 Import Excel/CSV
                </a>
                <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">
                    ➕ Tambah Obat Pertama
                </a>
            </div>
        @endif
    </div>
@endif
@endsection
