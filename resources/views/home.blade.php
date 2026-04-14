@extends('layouts.frontend')

@section('title', 'Beranda - Medikpedia')

@section('styles')
<style>
    /* ===== HERO SECTION ===== */
    .hero-section {
        background: linear-gradient(135deg, #0D47A1 0%, #1565C0 40%, #1976D2 70%, #1E88E5 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        padding: 4rem 0;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -100px;
        right: -100px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(124,179,66,0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -80px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.07) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-content { position: relative; z-index: 2; }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(124,179,66,0.2);
        border: 1px solid rgba(124,179,66,0.4);
        color: #a5d65a;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .hero-title {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 800;
        color: white;
        line-height: 1.2;
        margin-bottom: 1.25rem;
    }

    .hero-title .selamat-datang {
        display: block;
        font-size: clamp(0.85rem, 1.8vw, 1.1rem);
        font-weight: 600;
        color: rgba(255,255,255,0.7);
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .hero-title .brand-medik  { color: #ffffff; }
    .hero-title .brand-pedia  { color: #7CB342; }

    .hero-desc {
        font-size: 1rem;
        color: rgba(255,255,255,0.85);
        line-height: 1.8;
        margin-bottom: 1rem;
        max-width: 500px;
    }

    .hero-desc-sub {
        font-size: 0.88rem;
        color: rgba(255,255,255,0.7);
        line-height: 1.75;
        margin-bottom: 2rem;
        max-width: 500px;
        border-left: 3px solid rgba(124,179,66,0.7);
        padding-left: 0.85rem;
    }

    .hero-desc-sub strong {
        color: #a5d65a;
        font-size: 0.82rem;
        letter-spacing: 0.04em;
    }

    .hero-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }

    .btn-hero-primary {
        background: #7CB342;
        color: white;
        padding: 0.85rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(124,179,66,0.4);
    }

    .btn-hero-primary:hover {
        background: #689F38;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(124,179,66,0.5);
        color: white;
    }

    .btn-hero-outline {
        background: transparent;
        color: white;
        padding: 0.85rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1rem;
        border: 2px solid rgba(255,255,255,0.5);
        transition: all 0.3s;
    }

    .btn-hero-outline:hover {
        background: rgba(255,255,255,0.15);
        border-color: white;
        color: white;
        transform: translateY(-3px);
    }

        /* ===== HERO VISUAL ===== */
    .hero-visual {
        position: relative;
        height: 520px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Main image — mengisi penuh area visual */
    .main-hero-image {
        position: absolute;
        inset: 0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 64px rgba(0,0,0,0.35), 0 4px 16px rgba(0,0,0,0.15);
        border: 2px solid rgba(255,255,255,0.2);
        z-index: 5;
        transition: box-shadow 0.4s ease;
    }

    .main-hero-image:hover {
        box-shadow: 0 32px 80px rgba(0,0,0,0.45), 0 4px 20px rgba(0,0,0,0.2);
    }

    /* Gradient overlay kiri — menyatu dengan hero bg */
    .main-hero-image::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            90deg,
            rgba(13,71,161,0.55) 0%,
            rgba(21,101,192,0.25) 35%,
            transparent 65%
        );
        z-index: 2;
    }

    /* Garis aksen vertikal kiri */
    .main-hero-image::after {
        content: '';
        position: absolute;
        left: 0;
        top: 12%;
        bottom: 12%;
        width: 3px;
        background: linear-gradient(to bottom, transparent, rgba(124,179,66,0.9), rgba(255,255,255,0.7), rgba(124,179,66,0.9), transparent);
        z-index: 4;
        border-radius: 2px;
    }

    .main-hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease, opacity 0.3s ease;
    }

    .main-hero-image:hover img {
        transform: scale(1.03);
    }

    /* Caption bawah foto */
    .photo-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0.25) 60%, transparent 100%);
        color: white;
        padding: 2.5rem 1.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.9rem;
        z-index: 3;
    }

    .photo-caption .caption-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(124,179,66,0.9);
        color: white;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        margin-bottom: 0.4rem;
    }

    .photo-caption .caption-text {
        display: block;
        font-size: 0.95rem;
        font-weight: 600;
        color: rgba(255,255,255,0.95);
    }

    /* Slide indicators */
    .slide-indicators {
        position: absolute;
        bottom: 1.25rem;
        right: 1.25rem;
        display: flex;
        gap: 6px;
        z-index: 6;
    }

    .slide-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.6);
    }

    .slide-dot.active {
        background: #7CB342;
        width: 22px;
        border-radius: 4px;
        border-color: #7CB342;
    }

    /* Thumbnail strip — di bawah main image */
    .small-photos {
        position: absolute;
        bottom: -70px;
        left: 0;
        right: 0;
        display: flex;
        gap: 0.6rem;
        justify-content: center;
        z-index: 6;
    }

    .small-photo {
        width: 72px;
        height: 56px;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid rgba(255,255,255,0.5);
        cursor: pointer;
        transition: all 0.25s ease;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .small-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .small-photo:hover {
        border-color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }

    .small-photo.thumb-active {
        border-color: #7CB342;
        box-shadow: 0 0 0 2px #7CB342, 0 6px 16px rgba(0,0,0,0.25);
        transform: translateY(-3px);
    }

    /* Thumb overlay */
    .thumb-overlay {
        position: absolute;
        inset: 0;
        background: rgba(13,71,161,0.3);
        transition: background 0.3s;
        border-radius: 8px;
    }

    .small-photo.thumb-active .thumb-overlay {
        background: transparent;
    }

    /* Float stats */
    .float-stat {
        position: absolute;
        background: white;
        border-radius: 12px;
        padding: 0.65rem 0.9rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: floatUp 3s ease-in-out infinite;
    }

    .float-stat-1 {
        top: 1rem;
        left: -1rem;
        animation-delay: 0s;
    }

    .float-stat-2 {
        bottom: 5rem;
        right: -1rem;
        animation-delay: 1.5s;
    }

    .float-stat .stat-icon { font-size: 1.4rem; }
    .float-stat .stat-text strong { display: block; font-size: 0.95rem; font-weight: 700; color: #1f2937; }
    .float-stat .stat-text span { font-size: 0.72rem; color: #6b7280; }

    @keyframes floatUp {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* Decorative dot grid */
    .hero-dot-grid {
        position: absolute;
        top: -1rem;
        right: -1rem;
        z-index: 2;
        display: grid;
        grid-template-columns: repeat(5, 8px);
        gap: 6px;
        opacity: 0.3;
    }

    .hero-dot-grid span {
        width: 4px;
        height: 4px;
        background: white;
        border-radius: 50%;
        display: block;
    }

    .hero-line-accent { display: none; }

    /* Medicine icons */
    .medicine-icon {
        position: absolute;
        font-size: 1.4rem;
        color: rgba(124,179,66,0.7);
        animation: floatMedicine 4s ease-in-out infinite;
        z-index: 3;
        pointer-events: none;
    }

    .medicine-icon-1 { top: -1.5rem; left: -2rem; animation-delay: 0s; }
    .medicine-icon-2 { display: none; }
    .medicine-icon-3 { bottom: 4rem; left: -2rem; animation-delay: 2s; color: rgba(255,255,255,0.5); }
    .medicine-icon-4 { top: 40%; right: -2rem; animation-delay: 3s; color: rgba(165,214,90,0.6); }
    .medicine-icon-5 { display: none; }
    .medicine-icon-6 { display: none; }

    @keyframes floatMedicine {
        0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.7; }
        50% { transform: translateY(-12px) rotate(-5deg); opacity: 1; }
    }

    .hero-decoration { display: none; }

    /* Photo card legacy — hidden */
    .photo-card, .photo-card-back, .photo-card-mid, .photo-card-front { display: none !important; }
/* ===== STATS BAR ===== */
    .stats-bar {
        background: rgba(255,255,255,0.6);
        backdrop-filter: blur(8px);
        padding: 2rem 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .stat-item { text-align: center; padding: 0.5rem; }
    .stat-number { font-size: 2rem; font-weight: 800; color: #1E88E5; display: block; }
    .stat-label { font-size: 0.875rem; color: #6b7280; font-weight: 500; }

    /* ===== SEARCH SECTION ===== */
    .search-section-wrap {
        background: #f8faff;
        padding: 3rem 0 1rem;
    }

    .search-box {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        max-width: 700px;
        margin: 0 auto;
    }

    .search-form {
        display: flex;
        gap: 0.75rem;
    }

    .search-form input {
        flex: 1;
        padding: 0.8rem 1.1rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        font-size: 1rem;
        color: #374151;
        background: #f9fafb;
        transition: all 0.2s;
    }

    .search-form input:focus {
        outline: none;
        border-color: #1E88E5;
        background: white;
        box-shadow: 0 0 0 3px rgba(30,136,229,0.1);
    }

    .search-form button {
        padding: 0.8rem 1.75rem;
        background: linear-gradient(135deg, #1E88E5, #1565C0);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .search-form button:hover {
        background: linear-gradient(135deg, #1565C0, #0D47A1);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(30,136,229,0.3);
    }

    /* ===== PRODUCTS SECTION ===== */
    .products-section {
        padding: 2rem 0 4rem;
        background: transparent;
    }

    .section-label {
        display: inline-block;
        background: #e3f2fd;
        color: #1E88E5;
        padding: 0.35rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .section-heading {
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .medicines-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .medicine-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .medicine-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 35px rgba(30,136,229,0.12);
        border-color: #90caf9;
    }

    .medicine-image {
        width: 100%;
        height: 190px;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        overflow: hidden;
    }

    .medicine-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
    }

    .medicine-card:hover .medicine-image img { transform: scale(1.05); }

    .medicine-body { padding: 1.25rem; }

    .medicine-category {
        display: inline-block;
        background: #e3f2fd;
        color: #1565C0;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .medicine-name {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1f2937;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }

    .medicine-price {
        font-size: 1.2rem;
        font-weight: 800;
        color: #1E88E5;
        margin-bottom: 0.5rem;
    }

    .stock-badge {
        display: inline-block;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .stock-available { background: #d1fae5; color: #065f46; }
    .stock-low { background: #fef3c7; color: #92400e; }
    .stock-out { background: #fee2e2; color: #7f1d1d; }

    .medicine-btn {
        display: block;
        width: 100%;
        padding: 0.7rem;
        background: linear-gradient(135deg, #1E88E5, #1565C0);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.9rem;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s;
    }

    .medicine-btn:hover {
        background: linear-gradient(135deg, #1565C0, #0D47A1);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30,136,229,0.3);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }

    .empty-state-icon { font-size: 3.5rem; margin-bottom: 1rem; display: block; }
    .empty-state h3 { font-size: 1.4rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; }
    .empty-state p { color: #6b7280; }

    /* ===== PAGINATION ===== */
    .pagination-wrap {
        display: flex;
        justify-content: center;
        margin-top: 2.5rem;
    }

    .pagination-wrap .pagination {
        display: flex;
        gap: 0.4rem;
        align-items: center;
        flex-wrap: wrap;
        justify-content: center;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .pagination-wrap .page-item .page-link {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: 1.5px solid #e5e7eb;
        background: white;
        color: #374151;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    }

    .pagination-wrap .page-item .page-link:hover {
        background: #e3f2fd;
        border-color: #1E88E5;
        color: #1E88E5;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30,136,229,0.15);
    }

    .pagination-wrap .page-item.active .page-link {
        background: linear-gradient(135deg, #1E88E5, #1565C0);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 12px rgba(30,136,229,0.35);
    }

    .pagination-wrap .page-item.disabled .page-link {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #d1d5db;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Tombol prev/next lebih lebar */
    .pagination-wrap .page-item:first-child .page-link,
    .pagination-wrap .page-item:last-child .page-link {
        width: auto;
        padding: 0 1rem;
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .hero-section { min-height: auto; padding: 5rem 0 2.5rem; }
        .hero-visual { height: 260px; min-height: 260px; margin-top: 1.5rem; }
        .main-hero-image {
            position: relative;
            inset: auto;
            width: 100%;
            border-radius: 16px;
        }
        .main-hero-image:hover { transform: none; }
        .photo-caption { font-size: 0.85rem; padding: 1rem 1rem 0.75rem; }

        /* Foto kecil di mobile — sembunyikan */
        .small-photos { display: none !important; }

        /* Icon obat di mobile */
        .medicine-icon { font-size: 1rem; }
        .medicine-icon-1 { top: 10px; left: 10px; }
        .medicine-icon-2 { display: none; }
        .medicine-icon-3 { bottom: 10px; left: 10px; font-size: 0.9rem; }
        .medicine-icon-4 { top: 50%; right: 10px; font-size: 0.9rem; }
        .medicine-icon-5 { display: none; }
        .medicine-icon-6 { display: none; }

        .float-stat { display: none; }
        .hero-decoration { display: none; }
        .hero-dot-grid { display: none; }
        .hero-line-accent { display: none; }
        .hero-title { font-size: clamp(1.7rem, 6vw, 2.4rem); }
        .hero-desc { font-size: 0.92rem; }
        .search-form { flex-direction: column; }
        .medicines-grid { grid-template-columns: repeat(2, 1fr); gap: 0.85rem; }
    }

    @media (max-width: 480px) {
        .hero-section { padding: 4rem 0 2rem; min-height: auto; }
        .hero-visual { height: 200px; min-height: 200px; }
        .main-hero-image {
            position: relative;
            inset: auto;
            width: 100%;
            border-radius: 12px;
        }
        .photo-caption { display: none; }

        /* Foto kecil di HP kecil — sembunyikan */
        .small-photos { display: none !important; }

        /* Icon obat di HP kecil */
        .medicine-icon { font-size: 0.85rem; }
        .medicine-icon-1 { top: 8px; left: 8px; }
        .medicine-icon-2 { display: none; }
        .medicine-icon-3 { bottom: 8px; left: 8px; }
        .medicine-icon-4 { top: 50%; right: 8px; }
        .medicine-icon-5 { display: none; }
        .medicine-icon-6 { display: none; }

        .hero-title { font-size: clamp(1.4rem, 7vw, 1.8rem); }
        .hero-desc { font-size: 0.85rem; max-width: 100%; }
        .hero-buttons { flex-direction: column; gap: 0.75rem; }
        .btn-hero-primary, .btn-hero-outline { text-align: center; justify-content: center; padding: 0.75rem 1.5rem; }
        .hero-badge { font-size: 0.75rem; }
    }

    /* ===== ABOUT SECTION ===== */
    .about-section {
        padding: 4rem 0;
        background: transparent;
    }

    .about-content .section-label {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #e8f5e8;
        color: #2e7d32;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .about-description {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #4b5563;
        margin-bottom: 2.5rem;
    }

    .about-features {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1.25rem;
        background: #f8faff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .feature-item:hover {
        background: #f0f9ff;
        border-color: #bfdbfe;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.1);
    }

    .feature-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #1E88E5, #1565C0);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .feature-content h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .feature-content p {
        font-size: 0.95rem;
        color: #6b7280;
        line-height: 1.6;
        margin: 0;
    }

    .about-visual {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .about-image-main {
        position: relative;
        width: 350px;
        height: 350px;
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 20px 60px rgba(30, 136, 229, 0.15);
        border: 3px solid white;
    }

    .about-image-main img {
        max-width: 80%;
        max-height: 80%;
        object-fit: contain;
    }

    .about-badge {
        position: absolute;
        top: -15px;
        right: -15px;
        background: white;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border: 2px solid #7CB342;
    }

    .about-badge i {
        font-size: 1.25rem;
        color: #7CB342;
    }

    .about-badge strong {
        display: block;
        font-size: 0.9rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.2;
    }

    .about-badge span {
        font-size: 0.75rem;
        color: #6b7280;
        line-height: 1.2;
    }

    .about-stats-card {
        position: absolute;
        bottom: -20px;
        left: -20px;
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 12px 35px rgba(0,0,0,0.15);
        border: 1px solid #e5e7eb;
        min-width: 200px;
    }

    .stats-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }

    .stats-row:last-child {
        margin-bottom: 0;
    }

    .stat-mini {
        flex: 1;
        text-align: center;
    }

    .stat-mini .number {
        display: block;
        font-size: 1.25rem;
        font-weight: 800;
        color: #1E88E5;
        line-height: 1.2;
    }

    .stat-mini .label {
        font-size: 0.7rem;
        color: #6b7280;
        font-weight: 500;
        line-height: 1.2;
    }

    @media (max-width: 768px) {
        .about-section { padding: 2.5rem 0; }
        .about-image-main { width: 240px !important; height: 240px !important; margin: 0 auto; }
        .about-stats-card { position: static; margin-top: 1.25rem; left: auto; bottom: auto; }
        .feature-item { flex-direction: row; text-align: left; }
        .feature-icon { margin: 0; }
    }
</style>
@endsection

@section('content')

<!-- ===== HERO ===== -->
<section class="hero-section" id="hero-section">

    <!-- Partikel statis hero -->
    <div class="hero-particles" aria-hidden="true">
        <span class="hp hp-capsule" style="top:8%;left:5%;--r:20deg;--s:0.8;--o:0.18;"></span>
        <span class="hp hp-capsule" style="top:20%;left:82%;--r:-15deg;--s:1.0;--o:0.15;"></span>
        <span class="hp hp-capsule" style="top:55%;left:3%;--r:35deg;--s:0.7;--o:0.16;"></span>
        <span class="hp hp-capsule" style="top:75%;left:88%;--r:-25deg;--s:0.9;--o:0.14;"></span>
        <span class="hp hp-capsule" style="top:40%;left:92%;--r:10deg;--s:0.65;--o:0.13;"></span>
        <span class="hp hp-capsule" style="top:88%;left:20%;--r:-30deg;--s:0.75;--o:0.15;"></span>

        <span class="hp hp-tablet" style="top:15%;left:40%;--s:0.9;--o:0.16;"></span>
        <span class="hp hp-tablet" style="top:35%;left:70%;--s:0.75;--o:0.14;"></span>
        <span class="hp hp-tablet" style="top:65%;left:55%;--s:1.0;--o:0.13;"></span>
        <span class="hp hp-tablet" style="top:82%;left:75%;--s:0.8;--o:0.15;"></span>

        <span class="hp hp-pill" style="top:5%;left:60%;--s:1.1;--o:0.20;"></span>
        <span class="hp hp-pill" style="top:28%;left:15%;--s:0.9;--o:0.18;"></span>
        <span class="hp hp-pill" style="top:50%;left:78%;--s:0.8;--o:0.16;"></span>
        <span class="hp hp-pill" style="top:70%;left:30%;--s:1.0;--o:0.17;"></span>
        <span class="hp hp-pill" style="top:90%;left:60%;--s:0.85;--o:0.15;"></span>

        <span class="hp hp-cross" style="top:10%;left:25%;--s:0.9;--o:0.18;"></span>
        <span class="hp hp-cross" style="top:30%;left:95%;--s:0.75;--o:0.16;"></span>
        <span class="hp hp-cross" style="top:60%;left:18%;--s:1.0;--o:0.17;"></span>
        <span class="hp hp-cross" style="top:80%;left:48%;--s:0.8;--o:0.15;"></span>
        <span class="hp hp-cross" style="top:45%;left:42%;--s:0.65;--o:0.13;"></span>

        <i class="hp hp-icon fa-solid fa-pills"       style="top:12%;left:72%;--s:1.1;--o:0.18;"></i>
        <i class="hp hp-icon fa-solid fa-syringe"     style="top:38%;left:8%;--r:40deg;--s:1.0;--o:0.16;"></i>
        <i class="hp hp-icon fa-solid fa-capsules"    style="top:58%;left:65%;--s:0.9;--o:0.15;"></i>
        <i class="hp hp-icon fa-solid fa-heart-pulse" style="top:78%;left:10%;--s:1.0;--o:0.17;"></i>
        <i class="hp hp-icon fa-solid fa-flask"       style="top:22%;left:52%;--s:0.85;--o:0.14;"></i>
        <i class="hp hp-icon fa-solid fa-dna"         style="top:92%;left:85%;--s:0.9;--o:0.15;"></i>
    </div>

    <style>
        .hero-section { position: relative; overflow: hidden; }
        .hero-particles {
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
        }
        /* Semua konten hero di atas partikel */
        .hero-section .container { position: relative; z-index: 2; }

        .hp { position: absolute; transform: rotate(var(--r,0deg)) scale(var(--s,1)); opacity: var(--o,0.15); }

        /* Kapsul putih-hijau */
        .hp-capsule {
            width: 52px; height: 22px; border-radius: 11px;
            background: linear-gradient(90deg, rgba(255,255,255,0.9) 50%, rgba(124,179,66,0.9) 50%);
            box-shadow: inset 0 1px 3px rgba(255,255,255,0.4);
        }

        /* Tablet putih */
        .hp-tablet {
            width: 36px; height: 22px; border-radius: 11px;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.5);
            position: relative;
        }
        .hp-tablet::after {
            content: '';
            position: absolute;
            top: 50%; left: 20%; right: 20%;
            height: 1.5px;
            background: rgba(255,255,255,0.6);
            transform: translateY(-50%);
        }

        /* Pil bulat hijau */
        .hp-pill {
            width: 20px; height: 20px; border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, rgba(255,255,255,0.9), rgba(124,179,66,0.8));
            border: 1.5px solid rgba(255,255,255,0.4);
        }

        /* Plus putih */
        .hp-cross { width: 24px; height: 24px; position: relative; }
        .hp-cross::before, .hp-cross::after {
            content: ''; position: absolute;
            background: rgba(255,255,255,0.85); border-radius: 2px;
        }
        .hp-cross::before { width: 8px; height: 24px; left: 50%; transform: translateX(-50%); }
        .hp-cross::after  { width: 24px; height: 8px; top: 50%; transform: translateY(-50%); }

        /* Ikon FA putih/hijau */
        .hp-icon { font-size: 1.3rem; color: rgba(255,255,255,0.85); transform: rotate(var(--r,0deg)) scale(var(--s,1)); }
        .hp-icon:nth-child(odd)  { color: rgba(165,214,90,0.85); }
        .hp-icon:nth-child(3n)   { color: rgba(255,255,255,0.70); }
    </style>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 hero-content">
                <div class="hero-badge">
                    <i class="fa-solid fa-shield-halved"></i> Apotik Online Terpercaya
                </div>
                <h1 class="hero-title">
                    <span class="selamat-datang">Selamat Datang di</span>
                    <span class="brand-medik">Medik</span><span class="brand-pedia">pedia</span>
                </h1>
                <p class="hero-desc">
                    MEDIKPEDIA hadir sebagai rantai distribusi untuk menjembatani kebutuhan Masyarakat dengan para praktisi kesehatan (RS, Apotek, Dokter, Bidan, Klinik, dll) untuk kebutuhan medis berkualitas dengan harga yang terjangkau. Lebih dari sekadar Apotek, kami adalah mitra strategis terpercaya bagi praktisi kesehatan dalam mewujudkan masyarakat yang lebih sehat.
                </p>
                <p class="hero-desc-sub">
                    <strong>INTEGRITAS & TANGGUNG JAWAB</strong><br>
                    Kami menjamin kualitas produk melalui standar pengadaan yang ketat. Sejak 2016, kami mengelola rantai distribusi secara mandiri melalui kerjasama dengan PBF lokal dan Nasional untuk memastikan keamanan dan keaslian produk hingga ke tangan mitra dan masyarakat. Dedikasi ini diperkuat dengan konsistensi dan pelayanan yang telah terjalin dengan Mitra kami di seluruh Indonesia, sebagai bukti komitmen kami dalam menjalankan tata kelola distribusi farmasi yang unggul demi kepercayaan seluruh mitra layanan medis.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('products') }}" class="btn-hero-primary"><i class="fa-solid fa-pills"></i> Lihat Produk</a>
                    <a href="{{ route('news.index') }}" class="btn-hero-outline"><i class="fa-solid fa-tag"></i> Produk Promo</a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-visual">
                    <!-- Main image besar di pojok kanan (slideshow) -->
                    <div class="main-hero-image" id="main-hero-bg">
                        <img id="heroMainImg" src="{{ asset('page1.jpeg') }}" alt="Medikpedia">
                        <div class="photo-caption">
                            <span class="caption-badge"><i class="fa-solid fa-circle-check"></i> Terpercaya</span>
                            <span class="caption-text">Apotik & Distributor Resmi Farmasi</span>
                        </div>
                        <!-- Indikator slide -->
                        <div class="slide-indicators">
                            <span class="slide-dot active" onclick="goToSlide(0)"></span>
                            <span class="slide-dot" onclick="goToSlide(1)"></span>
                            <span class="slide-dot" onclick="goToSlide(2)"></span>
                            <span class="slide-dot" onclick="goToSlide(3)"></span>
                            <span class="slide-dot" onclick="goToSlide(4)"></span>
                            <span class="slide-dot" onclick="goToSlide(5)"></span>
                        </div>
                    </div>

                    <!-- Thumbnail strip di kiri -->
                    <div class="small-photos">
                        <div class="small-photo small-photo-1 thumb-active" onclick="goToSlide(0)">
                            <img src="{{ asset('page1.jpeg') }}" alt="Page 1">
                            <div class="thumb-overlay"></div>
                        </div>
                        <div class="small-photo small-photo-2" onclick="goToSlide(1)">
                            <img src="{{ asset('page2.jpeg') }}" alt="Page 2">
                            <div class="thumb-overlay"></div>
                        </div>
                        <div class="small-photo small-photo-3" onclick="goToSlide(2)">
                            <img src="{{ asset('page3.jpeg') }}" alt="Page 3">
                            <div class="thumb-overlay"></div>
                        </div>
                    </div>

                    <!-- Dot grid hiasan -->
                    <div class="hero-dot-grid">
                        @for($i = 0; $i < 25; $i++)
                            <span></span>
                        @endfor
                    </div>

                    <!-- Garis aksen -->
                    <div class="hero-line-accent hero-line-accent-1"></div>
                    <div class="hero-line-accent hero-line-accent-2"></div>

                    <!-- Floating medicine icons -->
                    <i class="fa-solid fa-pills medicine-icon medicine-icon-1"></i>
                    <i class="fa-solid fa-capsules medicine-icon medicine-icon-2"></i>
                    <i class="fa-solid fa-syringe medicine-icon medicine-icon-3"></i>
                    <i class="fa-solid fa-stethoscope medicine-icon medicine-icon-4"></i>
                    <i class="fa-solid fa-heart-pulse medicine-icon medicine-icon-5"></i>
                    <i class="fa-solid fa-user-doctor medicine-icon medicine-icon-6"></i>

                    <!-- Decorative elements -->
                    <div class="hero-decoration decoration-1"></div>
                    <div class="hero-decoration decoration-2"></div>

                    <!-- Float stats -->
                    <div class="float-stat float-stat-1">
                        <i class="fa-solid fa-pills" style="font-size:1.5rem;color:#1E88E5;"></i>
                        <div class="stat-text">
                            <strong>500+</strong>
                            <span>Produk Tersedia</span>
                        </div>
                    </div>
                    <div class="float-stat float-stat-2">
                        <i class="fa-solid fa-star" style="font-size:1.5rem;color:#f59e0b;"></i>
                        <div class="stat-text">
                            <strong>10K+</strong>
                            <span>Pelanggan Puas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
const heroImages = [
    '{{ asset("page1.jpeg") }}',
    '{{ asset("page2.jpeg") }}',
    '{{ asset("page3.jpeg") }}',
    '{{ asset("page4.jpeg") }}',
    '{{ asset("page5.jpeg") }}',
    '{{ asset("page6.jpeg") }}',
];

const thumbImages = [
    '{{ asset("page1.jpeg") }}',
    '{{ asset("page2.jpeg") }}',
    '{{ asset("page3.jpeg") }}',
    '{{ asset("page4.jpeg") }}',
    '{{ asset("page5.jpeg") }}',
    '{{ asset("page6.jpeg") }}',
];

// Mapping: thumb index 0,1,2 → slide index 0,1,2 (thumb hanya tampil 3)
const thumbSlots = [0, 1, 2]; // thumb-1, thumb-2, thumb-3 menampilkan slide ke-?
let currentSlide = 0;
let slideTimer;

function goToSlide(index) {
    currentSlide = index;

    // Ganti gambar utama dengan fade
    const mainImg = document.getElementById('heroMainImg');
    mainImg.style.opacity = '0';
    setTimeout(() => {
        mainImg.src = heroImages[index];
        mainImg.style.opacity = '1';
    }, 200);

    // Update dots
    document.querySelectorAll('.slide-dot').forEach((d, i) => {
        d.classList.toggle('active', i === index);
    });

    // Update thumbnails — tampilkan 3 gambar di sekitar slide aktif
    const thumbEls = document.querySelectorAll('.small-photo img');
    const thumbCards = document.querySelectorAll('.small-photo');

    // Hitung 3 thumb yang ditampilkan
    let start = Math.max(0, Math.min(index - 1, heroImages.length - 3));
    for (let t = 0; t < 3; t++) {
        const imgIdx = start + t;
        if (thumbEls[t]) thumbEls[t].src = thumbImages[imgIdx];
        if (thumbCards[t]) {
            thumbCards[t].classList.toggle('thumb-active', imgIdx === index);
        }
    }

    // Reset timer
    clearInterval(slideTimer);
    startAutoSlide();
}

function startAutoSlide() {
    slideTimer = setInterval(() => {
        goToSlide((currentSlide + 1) % heroImages.length);
    }, 3500);
}

// Mulai auto slide
document.addEventListener('DOMContentLoaded', () => {
    // Preload semua gambar
    heroImages.forEach(src => { const img = new Image(); img.src = src; });
    startAutoSlide();
});
</script>

<!-- ===== STATS BAR ===== -->
<div class="stats-bar">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number">10K+</span>
                    <span class="stat-label">Pelanggan Puas</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Produk Tersedia</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number">99%</span>
                    <span class="stat-label">Kepuasan Pelanggan</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number">24/7</span>
                    <span class="stat-label">Layanan Aktif</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== TENTANG KAMI SECTION ===== -->
<section class="about-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about-content">
                    <div class="section-label">
                        <i class="fa-solid fa-building"></i> Tentang Kami
                    </div>
                    <h2 class="section-heading">Distributor & Apotik Terpercaya di Indonesia</h2>
                    <p class="about-description">
                        <strong>Medikpedia</strong> adalah perusahaan distributor farmasi dan apotik online yang telah berpengalaman lebih dari 10 tahun dalam melayani kebutuhan kesehatan masyarakat Indonesia. Kami berkomitmen menyediakan obat-obatan berkualitas tinggi dengan harga terjangkau.
                    </p>
                    
                    <div class="about-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fa-solid fa-truck"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Distributor Resmi</h4>
                                <p>Kami adalah distributor resmi berbagai brand farmasi ternama dengan izin lengkap dari BPOM dan Kementerian Kesehatan.</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Apotik Online</h4>
                                <p>Layanan apotik online 24/7 dengan konsultasi gratis dari apoteker berpengalaman dan pengiriman ke seluruh Indonesia.</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div class="feature-content">
                                <h4>Kualitas Terjamin</h4>
                                <p>Semua produk kami tersimpan dalam kondisi optimal dan memiliki sertifikat halal serta standar GMP internasional.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="about-visual">
                    <div class="about-image-main">
                        <img src="{{ asset('logo1.png') }}" alt="Medikpedia Distributor">
                        <div class="about-badge">
                            <i class="fa-solid fa-certificate"></i>
                            <div>
                                <strong>Bersertifikat</strong>
                                <span>BPOM & Kemenkes</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="about-stats-card">
                        <div class="stats-row">
                            <div class="stat-mini">
                                <span class="number">15+</span>
                                <span class="label">Tahun Pengalaman</span>
                            </div>
                            <div class="stat-mini">
                                <span class="number">100+</span>
                                <span class="label">Brand Partner</span>
                            </div>
                        </div>
                        <div class="stats-row">
                            <div class="stat-mini">
                                <span class="number">50+</span>
                                <span class="label">Kota Jangkauan</span>
                            </div>
                            <div class="stat-mini">
                                <span class="number">24/7</span>
                                <span class="label">Customer Service</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
