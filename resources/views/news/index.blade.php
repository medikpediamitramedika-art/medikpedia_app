@extends('layouts.frontend')

@section('title', 'Produk Promo - Medikpedia')

@section('styles')
<style>
    /* ===== PAGE HEADER ===== */
    .news-page-header {
        background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1E88E5 100%);
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
    }

    .news-page-header::before {
        content: '';
        position: absolute;
        top: -80px;
        right: -80px;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(124,179,66,0.18) 0%, transparent 70%);
        border-radius: 50%;
    }

    .news-page-header::after {
        content: '';
        position: absolute;
        bottom: -50px;
        left: 10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
        border-radius: 50%;
    }

    .news-page-header .header-deco-icon {
        position: absolute;
        color: rgba(255,255,255,0.08);
        pointer-events: none;
        animation: headerIconFloat 6s ease-in-out infinite;
    }

    .news-page-header .header-deco-icon-1 { bottom: 10px; right: 12%; font-size: 4rem; animation-delay: 0s; }
    .news-page-header .header-deco-icon-2 { top: 15px;   right: 28%; font-size: 3rem; animation-delay: 2s; }
    .news-page-header .header-deco-icon-3 { bottom: 20px; right: 40%; font-size: 2.5rem; animation-delay: 4s; }

    @keyframes headerIconFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.08; }
        50%       { transform: translateY(-12px) rotate(8deg); opacity: 0.14; }
    }

    .news-page-header h1 {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        color: white;
        margin-bottom: 0.75rem;
        position: relative;
    }

    .news-page-header p {
        color: rgba(255,255,255,0.8);
        font-size: 1.1rem;
        position: relative;
    }

    .breadcrumb-custom {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        margin-bottom: 1rem;
        position: relative;
    }

    .breadcrumb-custom a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.2s;
    }

    .breadcrumb-custom a:hover { color: white; }
    .breadcrumb-custom span { color: rgba(255,255,255,0.5); font-size: 0.9rem; }
    .breadcrumb-custom .current { color: #a5d65a; font-size: 0.9rem; font-weight: 600; }

    /* ===== MAIN CONTENT ===== */
    .news-main {
        background: transparent;
        padding: 3rem 0 5rem;
        min-height: 60vh;
    }

    /* ===== FILTER ===== */
    .news-filter-bar {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 180px;
    }

    .filter-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #374151;
        font-size: 0.85rem;
    }

    .filter-input,
    .filter-select {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.95rem;
        color: #374151;
        background: #f9fafb;
        transition: all 0.2s;
    }

    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: #1E88E5;
        background: white;
        box-shadow: 0 0 0 3px rgba(30,136,229,0.1);
    }

    .btn-filter {
        padding: 0.7rem 1.5rem;
        background: linear-gradient(135deg, #1E88E5, #1565C0);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .btn-filter:hover {
        background: linear-gradient(135deg, #1565C0, #0D47A1);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30,136,229,0.3);
    }

    /* ===== NEWS GRID ===== */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.75rem;
        margin-bottom: 3rem;
    }

    .news-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .news-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(30,136,229,0.12);
        border-color: #90caf9;
        color: inherit;
    }

    .news-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #1E88E5, #0D47A1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        overflow: hidden;
        position: relative;
    }

    .news-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }

    .news-card:hover .news-image img {
        transform: scale(1.05);
    }

    .news-image video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        pointer-events: none;
    }

    /* Play button overlay untuk video */
    .video-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.35);
        transition: background 0.3s;
    }

    .news-card:hover .video-overlay {
        background: rgba(0,0,0,0.5);
    }

    .play-btn {
        width: 56px;
        height: 56px;
        background: rgba(255,255,255,0.92);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        transition: transform 0.3s;
        padding-left: 4px; /* optik segitiga */
    }

    .news-card:hover .play-btn {
        transform: scale(1.15);
    }

    .news-type-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background: rgba(13,71,161,0.85);
        backdrop-filter: blur(4px);
        color: white;
        padding: 0.3rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .news-type-badge.video-badge {
        background: rgba(159,18,57,0.85);
    }

    .news-type-badge.galeri-badge {
        background: rgba(22,101,52,0.85);
    }

    .news-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .news-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .news-date {
        color: #7CB342;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .news-dot {
        width: 4px;
        height: 4px;
        background: #d1d5db;
        border-radius: 50%;
    }

    .news-views-small {
        color: #9ca3af;
        font-size: 0.8rem;
    }

    .news-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: #1f2937;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.5;
    }

    .news-excerpt {
        color: #6b7280;
        font-size: 0.9rem;
        line-height: 1.7;
        margin-bottom: 1.25rem;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #f3f4f6;
    }

    .news-views {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: #9ca3af;
        font-size: 0.8rem;
    }

    .btn-read {
        background: linear-gradient(135deg, #1E88E5, #1565C0);
        color: white;
        padding: 0.45rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-read:hover {
        background: linear-gradient(135deg, #1565C0, #0D47A1);
        color: white;
        transform: translateX(2px);
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        display: block;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 1rem;
    }

    /* ===== PAGINATION ===== */
    .pagination-wrap {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .news-filter-bar {
            flex-direction: column;
            padding: 1rem;
            gap: 0.75rem;
        }

        .filter-group {
            width: 100%;
            min-width: unset;
        }

        .news-grid {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        .news-image {
            height: 180px;
        }
    }

    @media (max-width: 480px) {
        .news-body {
            padding: 1rem;
        }

        .news-title {
            font-size: 0.95rem;
        }
    }
</style>
@endsection

@section('content')

<!-- ===== HEADER ===== -->
<div class="news-page-header">
    <div class="container">
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            <span>/</span>
            <span class="current">Produk Promo</span>
        </div>
        <h1><i class="fa-solid fa-tag"></i> Produk Promo</h1>
        <p>Penawaran dan promo terbaik dari Medikpedia untuk Anda</p>
    </div>
    <i class="fa-solid fa-tag header-deco-icon header-deco-icon-1"></i>
    <i class="fa-solid fa-percent header-deco-icon header-deco-icon-2"></i>
    <i class="fa-solid fa-bolt header-deco-icon header-deco-icon-3"></i>
</div>

<!-- ===== MAIN ===== -->
<div class="news-main">
    <div class="container">

        <!-- Filter -->
        <form action="{{ route('news.index') }}" method="GET" class="news-filter-bar">
            <div class="filter-group" style="flex: 2;">
                <label class="filter-label"><i class="fa-solid fa-magnifying-glass"></i> Cari Produk Promo</label>
                <input
                    type="text"
                    name="search"
                    class="filter-input"
                    placeholder="Cari judul atau konten..."
                    value="{{ $search ?? '' }}"
                >
            </div>
            <div class="filter-group">
                <label class="filter-label"><i class="fa-solid fa-layer-group"></i> Jenis Konten</label>
                <select name="type" class="filter-select">
                    <option value="">Semua Jenis</option>
                    <option value="diskon"        @selected($type == 'diskon')>🏷️ Diskon</option>
                    <option value="flash_sale"    @selected($type == 'flash_sale')>⚡ Flash Sale</option>
                    <option value="bundling"      @selected($type == 'bundling')>📦 Bundling</option>
                    <option value="promo_spesial" @selected($type == 'promo_spesial')>🎁 Promo Spesial</option>
                </select>
            </div>
            <button type="submit" class="btn-filter"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
        </form>

        <!-- Grid -->
        @if($news->count() > 0)
            <div class="news-grid">
                @foreach($news as $item)
                    <a href="{{ route('news.show', $item->id) }}" class="news-card">
                        <div class="news-image">
                            @if($item->tipe === 'video' && $item->file)
                                {{-- Preview frame dari video --}}
                                <video muted preload="metadata" style="width:100%;height:100%;object-fit:cover;pointer-events:none;">
                                    <source src="{{ asset('storage/' . $item->file) }}#t=0.5" type="video/mp4">
                                </video>
                                <div class="video-overlay">
                                    <div class="play-btn">▶</div>
                                </div>
                            @elseif($item->thumbnail)
                                <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->judul }}">
                            @elseif($item->file && $item->tipe !== 'video')
                                <img src="{{ asset('storage/' . $item->file) }}" alt="{{ $item->judul }}">
                            @else
                                <div style="font-size:3.5rem; opacity:0.7;">
                                    @switch($item->tipe)
                                        @case('flash_sale') ⚡ @break
                                        @case('bundling')   📦 @break
                                        @case('promo_spesial') 🎁 @break
                                        @default 🏷️
                                    @endswitch
                                </div>
                            @endif
                            <div class="news-type-badge {{ $item->tipe === 'flash_sale' ? 'video-badge' : ($item->tipe === 'bundling' ? 'galeri-badge' : '') }}">
                                {{ $item->getTipeBadge() }}
                            </div>
                        </div>
                        <div class="news-body">
                            <div class="news-meta">
                                <span class="news-date">{{ $item->created_at->format('d M Y') }}</span>
                                <span class="news-dot"></span>
                        <span class="news-views-small"><i class="fa-regular fa-eye"></i> {{ $item->views }}</span>
                            </div>
                            <h3 class="news-title">{{ $item->judul }}</h3>
                            <p class="news-excerpt">{{ $item->deskripsi }}</p>
                            <div class="news-footer">
                                <div class="news-views">
                                <i class="fa-regular fa-eye"></i> {{ $item->views }} kali dibaca
                            </div>
                                <span class="btn-read">Baca →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination-wrap">
                {{ $news->links('pagination::bootstrap-4') }}
            </div>
        @else
            <div class="empty-state">
                <span class="empty-icon"><i class="fa-solid fa-inbox" style="color:#d1d5db;font-size:4rem;"></i></span>
                <h3>Tidak ada produk promo</h3>
                <p>
                    @if($search || $type)
                        Coba ubah filter pencarian Anda
                    @else
                        Produk promo akan segera hadir. Silakan cek kembali nanti.
                    @endif
                </p>
            </div>
        @endif

    </div>
</div>

@endsection
