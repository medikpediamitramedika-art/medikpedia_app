@extends('layouts.frontend')

@section('title', $news->judul . ' - Medikpedia')

@section('styles')
<style>
    .news-detail-header {
        background: linear-gradient(135deg, #1E88E5 0%, #7CB342 100%);
        color: white;
        padding: 3rem 1rem;
        margin-bottom: 2rem;
    }

    .news-detail-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .news-detail-title {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .news-detail-meta {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .news-detail-content {
        background: white;
        padding: 2rem;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .news-highlight {
        background: linear-gradient(135deg, #EFF6FF, #F0FDF4);
        border-left: 4px solid #1E88E5;
        padding: 1.5rem;
        margin: 1.5rem 0;
        border-radius: 0.5rem;
    }

    .news-body-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #4b5563;
    }

    .news-body-content p {
        margin-bottom: 1.5rem;
    }

    .news-body-content h3 {
        color: #1E88E5;
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .news-body-content ul,
    .news-body-content ol {
        margin-left: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .news-body-content li {
        margin-bottom: 0.5rem;
    }

    .news-image {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }

    .news-video {
        width: 100%;
        aspect-ratio: 16/9;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }

    .news-sidebar {
        background: white;
        padding: 2rem;
        border-radius: 0.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-top: 4px solid #1E88E5;
        margin-bottom: 2rem;
    }

    .sidebar-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1f2937;
    }

    .sidebar-item {
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        padding: 1.25rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid #7CB342;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .sidebar-item:hover {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        transform: translateX(6px);
        box-shadow: 0 4px 12px rgba(124, 179, 66, 0.15);
        border-left-color: #5aa82a;
    }

    .sidebar-item-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
        background: #f3f4f6;
    }

    .sidebar-item-title {
        font-weight: 700;
        color: #1f2937;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .sidebar-item-meta {
        display: flex;
        gap: 0.75rem;
        font-size: 0.75rem;
        color: #9ca3af;
        flex-wrap: wrap;
        align-items: center;
    }

    .sidebar-item-date {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .sidebar-item-views {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .related-news-footer {
        text-align: center;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .view-all-link {
        color: #1E88E5;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
    }

    .view-all-link:hover {
        color: #1565C0;
        gap: 0.75rem;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #1E88E5;
        text-decoration: none;
        margin-bottom: 1.5rem;
        font-weight: 600;
        transition: color 0.3s;
    }

    .back-button:hover {
        color: #1565C0;
    }

    .fullscreen-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 2rem;
    }

    @media (max-width: 768px) {
        .news-detail-title {
            font-size: 1.5rem;
        }

        .news-detail-meta {
            flex-direction: column;
            gap: 0.5rem;
        }

        .fullscreen-layout {
            grid-template-columns: 1fr;
        }

        .news-detail-content {
            padding: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="news-detail-header">
    <div class="news-detail-container">
        <a href="{{ route('news.index') }}" class="back-button" style="color: white;">← Kembali ke Berita</a>
        <h1 class="news-detail-title">{{ $news->judul }}</h1>
        <div class="news-detail-meta">
            <span>📅 {{ $news->created_at->format('d F Y') }}</span>
            <span>👁️ {{ $news->views }} kali dibaca</span>
            <span>{{ $news->getTipeBadge() }}</span>
        </div>
    </div>
</div>

<div class="news-detail-container fullscreen-layout">
    <!-- Main Content -->
    <div>
        <a href="{{ route('news.index') }}" class="back-button">← Kembali ke Berita</a>

        <div class="news-detail-content">
            <!-- Media -->
            @if($news->file)
                @if($news->tipe === 'video')
                    @php
                        $videoExt = strtolower(pathinfo($news->file, PATHINFO_EXTENSION));
                        $mimeType = match($videoExt) {
                            'mp4' => 'video/mp4',
                            'webm' => 'video/webm',
                            'mov' => 'video/quicktime',
                            'avi' => 'video/x-msvideo',
                            'mkv' => 'video/x-matroska',
                            default => 'video/mp4'
                        };
                    @endphp
                    <div style="position:relative; background:#000; border-radius:0.75rem; overflow:hidden; margin-bottom:2rem; box-shadow:0 8px 32px rgba(0,0,0,0.18);">
                        <video
                            controls
                            preload="metadata"
                            @if($news->thumbnail) poster="{{ asset('storage/' . $news->thumbnail) }}" @endif
                            style="width:100%; max-height:480px; display:block; background:#000;"
                        >
                            <source src="{{ asset('storage/' . $news->file) }}" type="{{ $mimeType }}">
                            <p style="color:#fff; padding:1rem;">
                                Browser Anda tidak mendukung pemutar video.
                                <a href="{{ asset('storage/' . $news->file) }}" style="color:#90caf9;">Download video</a>
                            </p>
                        </video>
                    </div>
                @else
                    <img src="{{ asset('storage/' . $news->file) }}" alt="{{ $news->judul }}" class="news-image">
                @endif
            @elseif($news->thumbnail)
                <img src="{{ asset('storage/' . $news->thumbnail) }}" alt="{{ $news->judul }}" class="news-image">
            @else
                <div class="news-highlight">
                    <div style="font-size: 4rem; text-align: center;">
                        @switch($news->tipe)
                            @case('video') 🎬 @break
                            @case('galeri') 🖼️ @break
                            @default 📰
                        @endswitch
                    </div>
                </div>
            @endif

            <!-- Content -->
            <div class="news-body-content">
                {!! nl2br(e($news->konten)) !!}
            </div>

            <!-- Share & Actions -->
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                <p style="color: #6b7280; margin-bottom: 1rem;">Bagikan artikel ini:</p>
                <div style="display: flex; gap: 1rem;">
                    <a href="https://facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-secondary" style="background: #1877F2;">
                        📘 Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $news->judul }}" target="_blank" class="btn btn-secondary" style="background: #1DA1F2;">
                        𝕏 Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($news->judul . ' ' . url()->current()) }}" target="_blank" class="btn btn-secondary" style="background: #25D366;">
                        💬 WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <aside>
        @if($relatedNews->count() > 0)
            <div class="news-sidebar">
                <div class="sidebar-title">📰 Berita Terkait</div>
                @foreach($relatedNews as $related)
                    <a href="{{ route('news.show', $related->id) }}" class="sidebar-item">
                        <div style="position:relative; overflow:hidden; border-radius:0.5rem; margin-bottom:0.75rem;">
                            @if($related->thumbnail)
                                <img src="{{ asset('storage/' . $related->thumbnail) }}" alt="{{ $related->judul }}" class="sidebar-item-image" style="margin-bottom:0;">
                            @elseif($related->file && $related->tipe !== 'video')
                                <img src="{{ asset('storage/' . $related->file) }}" alt="{{ $related->judul }}" class="sidebar-item-image" style="margin-bottom:0;">
                            @else
                                <div class="sidebar-item-image" style="display:flex;align-items:center;justify-content:center;font-size:2rem;background:linear-gradient(135deg,#E3F2FD,#F0FDF4);margin-bottom:0;">
                                    @switch($related->tipe)
                                        @case('video') 🎬 @break
                                        @case('galeri') 🖼️ @break
                                        @default 📰
                                    @endswitch
                                </div>
                            @endif
                            @if($related->tipe === 'video')
                                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.3);">
                                    <div style="width:36px;height:36px;background:rgba(255,255,255,0.9);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.9rem;padding-left:3px;">▶</div>
                                </div>
                            @endif
                        </div>
                        <div class="sidebar-item-title">{{ $related->judul }}</div>
                        <div class="sidebar-item-meta">
                            <div class="sidebar-item-date">📅 {{ $related->created_at->format('d M Y') }}</div>
                            <div class="sidebar-item-views">👁️ {{ $related->views }}</div>
                        </div>
                    </a>
                @endforeach
                <div class="related-news-footer">
                    <a href="{{ route('news.index') }}" class="view-all-link">
                        Lihat Semua Berita →
                    </a>
                </div>
            </div>
        @endif

        <div class="news-sidebar">
            <div class="sidebar-title">🏥 Layanan Kami</div>
            
            <!-- Service 1: Belanja Obat -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: linear-gradient(135deg, #E3F2FD, #F0FDF4); border-radius: 0.5rem; border-left: 4px solid #1E88E5;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <span style="font-size: 1.5rem;">💊</span>
                    <div style="font-weight: 700; color: #1E88E5;">Belanja Obat</div>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem; line-height: 1.5;">
                    Akses ribuan obat original dengan harga terjangkau. Gratis konsultasi dengan apoteker kami.
                </p>
                <a href="{{ route('home') }}" class="btn btn-primary" style="width: 100%; text-align: center; background: #1E88E5; color: white; padding: 0.75rem; border-radius: 0.375rem; text-decoration: none; font-weight: 600; display: block; transition: all 0.3s;">
                    Mulai Belanja →
                </a>
            </div>
            
            <!-- Service 2: Tentang Kami -->
            <div style="margin-bottom: 1.5rem; padding: 1rem; background: linear-gradient(135deg, #F0FDF4, #FFFBEB); border-radius: 0.5rem; border-left: 4px solid #7CB342;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <span style="font-size: 1.5rem;">ℹ️</span>
                    <div style="font-weight: 700; color: #7CB342;">Tentang Kami</div>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem; line-height: 1.5;">
                    Pelajari lebih lanjut tentang visi, misi, dan komitmen kami dalam layanan kesehatan.
                </p>
                <a href="{{ route('about') }}" class="btn btn-primary" style="width: 100%; text-align: center; background: #7CB342; color: white; padding: 0.75rem; border-radius: 0.375rem; text-decoration: none; font-weight: 600; display: block; transition: all 0.3s;">
                    Pelajari Lebih Lanjut →
                </a>
            </div>

            <!-- Service 3: Konsultasi -->
            <div style="padding: 1rem; background: linear-gradient(135deg, #FEF3C7, #FCE7F3); border-radius: 0.5rem; border-left: 4px solid #F59E0B;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <span style="font-size: 1.5rem;">💬</span>
                    <div style="font-weight: 700; color: #D97706;">Butuh Bantuan?</div>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem; line-height: 1.5;">
                    Tim apoteker kami siap membantu 24/7 untuk pertanyaan kesehatan Anda.
                </p>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <a href="https://wa.me/0862345678" target="_blank" class="btn btn-primary" style="text-align: center; background: #25D366; color: white; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; font-weight: 600; font-size: 0.875rem; display: block; transition: all 0.3s;">
                        WhatsApp
                    </a>
                    <a href="tel:+62123456789" class="btn btn-primary" style="text-align: center; background: #1E88E5; color: white; padding: 0.5rem 0.75rem; border-radius: 0.375rem; text-decoration: none; font-weight: 600; font-size: 0.875rem; display: block; transition: all 0.3s;">
                        Telepon
                    </a>
                </div>
            </div>
        </div>
    </aside>
</div>
@endsection
