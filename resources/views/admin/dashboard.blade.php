@extends('layouts.admin')

@section('title', 'Dashboard - Admin Medikpedia')
@section('page-title', '📊 Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="stats-grid" style="margin-top: 1rem;">
    <div class="stat-card">
        <div class="stat-label">Total Obat</div>
        <div class="stat-value">{{ $totalMedicines }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Stok Obat</div>
        <div class="stat-value">{{ $totalStok }}</div>
    </div>
    <div class="stat-card" style="border-top-color: #f59e0b;">
        <div class="stat-label">Stok Rendah</div>
        <div class="stat-value" style="color: #f59e0b;">{{ $lowStokMedicines }}</div>
    </div>
    <div class="stat-card" style="border-top-color: #3b82f6;">
        <div class="stat-label">Total Produk Promo</div>
        <div class="stat-value" style="color: #3b82f6;">{{ $totalNews }}</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-title">⚡ Aksi Cepat</div>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;" class="quick-actions-grid">
        <a href="{{ route('admin.medicines.create') }}" class="btn btn-primary">
            ➕ Tambah Obat Baru
        </a>
        <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
            📋 Kelola Obat
        </a>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary" style="background: #7CB342;">
            ➕ Tambah Produk Promo
        </a>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
            🏷️ Kelola Produk Promo
        </a>
    </div>
</div>

<!-- Recent Medicines -->
<div class="card">
    <div class="card-title">🆕 Obat Terbaru</div>
    
    @if($medicines->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Perusahaan</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Tanggal Ditambah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medicines as $medicine)
                        <tr>
                            <td>
                                <strong>{{ $medicine->nama_obat }}</strong>
                            </td>
                            <td>{{ $medicine->kategori }}</td>
                            <td>{{ $medicine->getFormattedPrice() }}</td>
                            <td>
                                @if($medicine->stok > 10)
                                    <span style="color: #10b981;"><strong>{{ $medicine->stok }}</strong></span>
                                @elseif($medicine->stok > 0)
                                    <span style="color: #f59e0b;"><strong>{{ $medicine->stok }}</strong></span>
                                @else
                                    <span style="color: #ef4444;"><strong>{{ $medicine->stok }}</strong></span>
                                @endif
                            </td>
                            <td>{{ $medicine->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.medicines.edit', $medicine->id) }}" class="btn btn-secondary btn-sm">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p style="text-align: center; color: #6b7280; padding: 2rem;">
            Belum ada obat. <a href="{{ route('admin.medicines.create') }}" style="color: #10b981; font-weight: 600;">Tambah obat sekarang</a>
        </p>
    @endif
</div>

<!-- Categories Info -->
@if($categories->count() > 0)
    <div class="card">
        <div class="card-title">📂 Perusahaan Obat</div>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
            @foreach($categories as $category)
                <div style="
                    background: #f0fdf4;
                    padding: 1rem;
                    border-radius: 0.5rem;
                    border-left: 4px solid #10b981;
                    flex: 1;
                    min-width: 150px;
                ">
                    <div style="font-weight: 600; color: #10b981;">{{ $category }}</div>
                    <div style="font-size: 0.875rem; color: #6b7280;">
                        {{ \App\Models\Medicine::where('kategori', $category)->count() }} obat
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Latest News -->
<div class="card">
    <div class="card-title">🏷️ Produk Promo Terbaru</div>
    
    @if($latestNews->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Judul Produk Promo</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestNews as $news)
                        <tr>
                            <td>
                                <strong>{{ \Str::limit($news->judul, 30) }}</strong>
                            </td>
                            <td>
                                @if($news->tipe === 'diskon')
                                    <span style="background: #FEF3C7; color: #92400E; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem;">🏷️ Diskon</span>
                                @elseif($news->tipe === 'flash_sale')
                                    <span style="background: #FCE7F3; color: #9F1239; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem;">⚡ Flash Sale</span>
                                @elseif($news->tipe === 'bundling')
                                    <span style="background: #E0E7FF; color: #3730A3; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem;">📦 Bundling</span>
                                @else
                                    <span style="background: #F0FDF4; color: #166534; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem;">🎁 Promo Spesial</span>
                                @endif
                            </td>
                            <td>
                                @if($news->is_published)
                                    <span style="color: #10b981; font-weight: 600;">✓ Dipublikasi</span>
                                @else
                                    <span style="color: #f59e0b; font-weight: 600;">⚠ Draft</span>
                                @endif
                            </td>
                            <td><strong>{{ $news->views }}</strong></td>
                            <td>{{ $news->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-secondary btn-sm">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p style="text-align: center; color: #6b7280; padding: 2rem;">
            Belum ada produk promo. <a href="{{ route('admin.news.create') }}" style="color: #1E88E5; font-weight: 600;">Tambah produk promo sekarang</a>
        </p>
    @endif
</div>
@endsection
