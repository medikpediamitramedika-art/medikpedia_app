@extends('layouts.frontend')

@section('title', 'Aktivitas - Medikpedia')

@section('styles')
<style>
    .act-header {
        background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1E88E5 100%);
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
    }
    .act-header::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(124,179,66,0.18) 0%, transparent 70%);
        border-radius: 50%;
    }
    .act-header .header-deco-icon {
        position: absolute;
        color: rgba(255,255,255,0.08);
        pointer-events: none;
        animation: headerIconFloat 6s ease-in-out infinite;
    }
    .act-header .header-deco-icon-1 { bottom: 10px; right: 12%; font-size: 4rem; animation-delay: 0s; }
    .act-header .header-deco-icon-2 { top: 15px;   right: 28%; font-size: 3rem; animation-delay: 2s; }
    .act-header .header-deco-icon-3 { bottom: 20px; right: 40%; font-size: 2.5rem; animation-delay: 4s; }
    @keyframes headerIconFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.08; }
        50%       { transform: translateY(-12px) rotate(8deg); opacity: 0.14; }
    }
    .act-header h1 { font-size: clamp(2rem,4vw,3rem); font-weight: 800; color: white; margin-bottom: 0.5rem; position: relative; }
    .act-header p  { color: rgba(255,255,255,0.8); font-size: 1rem; position: relative; }
    .breadcrumb-custom { display: flex; gap: 0.5rem; align-items: center; margin-bottom: 1rem; position: relative; }
    .breadcrumb-custom a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; }
    .breadcrumb-custom a:hover { color: white; }
    .breadcrumb-custom span { color: rgba(255,255,255,0.5); font-size: 0.9rem; }
    .breadcrumb-custom .current { color: #a5d65a; font-size: 0.9rem; font-weight: 600; }

    .act-main { background: #f8faff; padding: 3rem 0 5rem; min-height: 60vh; }

    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.25rem;
    }

    .photo-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        transition: all 0.3s;
        cursor: pointer;
    }

    .photo-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(30,136,229,0.15);
        border-color: #90caf9;
    }

    .photo-wrap {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .photo-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }

    .photo-card:hover .photo-wrap img { transform: scale(1.06); }

    .photo-overlay {
        position: absolute;
        inset: 0;
        background: rgba(13,71,161,0);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
    }

    .photo-card:hover .photo-overlay { background: rgba(13,71,161,0.35); }

    .photo-overlay i {
        color: white;
        font-size: 2rem;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .photo-card:hover .photo-overlay i { opacity: 1; }

    .photo-body { padding: 1rem 1.1rem; }
    .photo-date { font-size: 0.78rem; color: #7CB342; font-weight: 600; margin-bottom: 0.3rem; }
    .photo-title { font-size: 0.95rem; font-weight: 700; color: #1f2937; margin-bottom: 0.3rem;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .photo-desc { font-size: 0.82rem; color: #6b7280; line-height: 1.6;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    /* Lightbox */
    .lightbox {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.92);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .lightbox.active { display: flex; }
    .lightbox-inner { position: relative; max-width: 900px; width: 100%; }
    .lightbox-inner img { width: 100%; max-height: 80vh; object-fit: contain; border-radius: 8px; }
    .lightbox-close {
        position: absolute;
        top: -2.5rem; right: 0;
        color: white; font-size: 1.75rem;
        cursor: pointer; background: none; border: none;
        line-height: 1;
    }
    .lightbox-caption { color: white; text-align: center; margin-top: 0.75rem; font-size: 0.95rem; }
    .lightbox-caption small { color: rgba(255,255,255,0.6); font-size: 0.8rem; }

    /* Pagination */
    .pagination-wrap { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-top: 2rem; }
    .pagination-wrap .info { color: #6b7280; font-size: 0.875rem; }
    .pagination-btns { display: flex; gap: 0.35rem; align-items: center; }
    .page-btn { padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: white; color: #374151; font-size: 0.875rem; text-decoration: none; border: 1px solid #e5e7eb; min-width: 36px; text-align: center; transition: all 0.2s; }
    .page-btn:hover  { background: #1E88E5; color: white; border-color: #1E88E5; }
    .page-btn.active { background: #1E88E5; color: white; border-color: #1E88E5; font-weight: 700; }
    .page-btn.disabled { background: #f3f4f6; color: #d1d5db; cursor: not-allowed; pointer-events: none; }

    @media (max-width: 768px) {
        .photo-grid { grid-template-columns: repeat(2, 1fr); gap: 0.85rem; }
        .photo-wrap { height: 170px; }
        .lightbox-inner img { max-height: 60vh; }
        .lightbox-close { top: -2rem; font-size: 1.5rem; }
    }

    @media (max-width: 480px) {
        .photo-grid { grid-template-columns: repeat(2, 1fr); gap: 0.6rem; }
        .photo-wrap { height: 130px; }
        .photo-body { padding: 0.65rem 0.75rem; }
        .photo-title { font-size: 0.85rem; }
        .photo-desc { display: none; }
    }
</style>
@endsection

@section('content')

<div class="act-header">
    <div class="container">
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            <span>/</span>
            <span class="current">Aktivitas</span>
        </div>
        <h1><i class="fa-solid fa-camera"></i> Aktivitas Kami</h1>
        <p>Dokumentasi kunjungan dan pembagian brosur ke apotek-apotek mitra</p>
    </div>
    <i class="fa-solid fa-camera header-deco-icon header-deco-icon-1"></i>
    <i class="fa-solid fa-image header-deco-icon header-deco-icon-2"></i>
    <i class="fa-solid fa-photo-film header-deco-icon header-deco-icon-3"></i>
</div>

<div class="act-main">
    <div class="container">

        @if($activities->count() > 0)
            <div class="photo-grid">
                @foreach($activities as $act)
                    <div class="photo-card" onclick="openLightbox('{{ asset('public/storage/' . $act->foto) }}', '{{ addslashes($act->judul) }}', '{{ $act->tanggal->format('d M Y') }}')">
                        <div class="photo-wrap">
                            <img src="{{ asset('public/storage/' . $act->foto) }}" alt="{{ $act->judul }}" loading="lazy">
                            <div class="photo-overlay">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </div>
                        </div>
                        <div class="photo-body">
                            <div class="photo-date"><i class="fa-regular fa-calendar"></i> {{ $act->tanggal->format('d M Y') }}</div>
                            <div class="photo-title">{{ $act->judul }}</div>
                            @if($act->deskripsi)
                                <div class="photo-desc">{{ $act->deskripsi }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrap">
                <p class="info">Halaman {{ $activities->currentPage() }} dari {{ $activities->lastPage() }}</p>
                <div class="pagination-btns">
                    @if($activities->onFirstPage())
                        <span class="page-btn disabled">‹</span>
                    @else
                        <a href="{{ $activities->previousPageUrl() }}" class="page-btn">‹</a>
                    @endif
                    @foreach($activities->getUrlRange(1, $activities->lastPage()) as $page => $url)
                        @if($page == $activities->currentPage())
                            <span class="page-btn active">{{ $page }}</span>
                        @elseif($page == 1 || $page == $activities->lastPage() || abs($page - $activities->currentPage()) <= 2)
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @elseif(abs($page - $activities->currentPage()) == 3)
                            <span class="page-btn disabled">…</span>
                        @endif
                    @endforeach
                    @if($activities->hasMorePages())
                        <a href="{{ $activities->nextPageUrl() }}" class="page-btn">›</a>
                    @else
                        <span class="page-btn disabled">›</span>
                    @endif
                </div>
            </div>

        @else
            <div style="text-align:center;padding:5rem 2rem;background:white;border-radius:16px;border:1px solid #e5e7eb;">
                <i class="fa-solid fa-camera" style="font-size:3.5rem;color:#d1d5db;"></i>
                <h3 style="font-size:1.4rem;font-weight:700;color:#1f2937;margin:1rem 0 0.5rem;">Belum ada foto aktivitas</h3>
                <p style="color:#6b7280;">Foto aktivitas akan segera hadir.</p>
            </div>
        @endif

    </div>
</div>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox" onclick="closeLightbox(event)">
    <div class="lightbox-inner">
        <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
        <img id="lightboxImg" src="" alt="">
        <div class="lightbox-caption">
            <div id="lightboxTitle"></div>
            <small id="lightboxDate"></small>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function openLightbox(src, title, date) {
    document.getElementById('lightboxImg').src   = src;
    document.getElementById('lightboxTitle').textContent = title;
    document.getElementById('lightboxDate').textContent  = date;
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox(e) {
    if (!e || e.target === document.getElementById('lightbox') || e.target.classList.contains('lightbox-close')) {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>
@endsection
