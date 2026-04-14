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

    /* ===== PHOTO CARDS STACK ===== */
    .hero-visual {
        position: relative;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        height: 480px;
    }

    /* Main image di pojok kanan */
    .main-hero-image {
        position: absolute;
        right: -60px;
        top: -350px;
        bottom: -60px;
        width: 480px;
        z-index: 5;
        overflow: hidden;
        border-radius: 28px 0 0 28px;
        box-shadow: -30px 0 80px rgba(0,0,0,0.35), -5px 0 20px rgba(0,0,0,0.15);
        border-left: 1px solid rgba(255,255,255,0.25);
        transition: all 0.4s ease;
    }

    .main-hero-image:hover {
        box-shadow: -35px 0 100px rgba(0,0,0,0.45), -5px 0 25px rgba(0,0,0,0.2);
    }

    /* Gradient overlay menyatu dengan background biru */
    .main-hero-image::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            105deg,
            rgba(13,71,161,0.75) 0%,
            rgba(21,101,192,0.45) 25%,
            rgba(25,118,210,0.2) 50%,
            transparent 75%
        );
        z-index: 2;
    }

    /* Garis aksen vertikal di sisi kiri foto */
    .main-hero-image::after {
        content: '';
        position: absolute;
        left: 0;
        top: 15%;
        bottom: 15%;
        width: 3px;
        background: linear-gradient(to bottom, transparent, rgba(124,179,66,0.8), rgba(255,255,255,0.6), rgba(124,179,66,0.8), transparent);
        z-index: 4;
        border-radius: 2px;
    }

    .main-hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: opacity 0.3s ease, transform 0.5s ease;
    }

    .main-hero-image:hover img {
        transform: scale(1.04);
    }

    /* Slide indicators */
    .slide-indicators {
        position: absolute;
        bottom: 70px;
        left: 20px;
        display: flex;
        gap: 6px;
        z-index: 5;
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

    /* Thumb overlay */
    .thumb-overlay {
        position: absolute;
        inset: 0;
        background: rgba(13,71,161,0.35);
        transition: all 0.3s;
        border-radius: 13px;
    }

    .small-photo.thumb-active .thumb-overlay {
        background: transparent;
    }

    .small-photo {
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .small-photo:hover {
        transform: scale(1.06) rotate(0deg) !important;
        box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    }

    .small-photo.thumb-active {
        box-shadow: 0 0 0 3px #7CB342, 0 15px 40px rgba(0,0,0,0.25) !important;
    }

    /* Caption bawah foto */
    .photo-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.3) 60%, transparent 100%);
        color: white;
        padding: 2rem 1.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.9rem;
        z-index: 3;
        letter-spacing: 0.02em;
    }

    .photo-caption .caption-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(124,179,66,0.85);
        color: white;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        margin-bottom: 0.4rem;
        letter-spacing: 0.03em;
    }

    .photo-caption .caption-text {
        display: block;
        font-size: 0.95rem;
        font-weight: 600;
        color: rgba(255,255,255,0.95);
    }

    /* Hiasan: dot grid di pojok kiri atas hero visual */
    .hero-dot-grid {
        position: absolute;
        top: 10px;
        left: 260px;
        z-index: 2;
        display: grid;
        grid-template-columns: repeat(5, 8px);
        gap: 6px;
        opacity: 0.25;
    }

    .hero-dot-grid span {
        width: 4px;
        height: 4px;
        background: white;
        border-radius: 50%;
        display: block;
    }

    /* Hiasan: garis horizontal tipis */
    .hero-line-accent {
        position: absolute;
        z-index: 2;
    }

    .hero-line-accent-1 {
        top: 30px;
        left: 240px;
        width: 80px;
        height: 2px;
        background: linear-gradient(to right, rgba(124,179,66,0.7), transparent);
        border-radius: 2px;
    }

    .hero-line-accent-2 {
        bottom: 60px;
        left: 220px;
        width: 60px;
        height: 2px;
        background: linear-gradient(to right, rgba(255,255,255,0.4), transparent);
        border-radius: 2px;
    }

    /* Pastikan konten hero tetap di atas gambar */
    .hero-content {
        position: relative;
        z-index: 10;
    }

    /* Small photos di kiri */
    .small-photos {
        position: absolute;
        left: 0;
        top: 0;
        width: 250px;
        height: 100%;
        z-index: 3;
    }

    .small-photo {
        position: absolute;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        border: 3px solid white;
        z-index: 4;
    }

    .small-photo-1 {
        width: 120px;
        height: 140px;
        left: 0;
        top: 20px;
        transform: rotate(-8deg);
    }

    .small-photo-2 {
        width: 100px;
        height: 120px;
        left: 80px;
        top: 180px;
        transform: rotate(12deg);
    }

    .small-photo-3 {
        width: 90px;
        height: 110px;
        left: 20px;
        top: 320px;
        transform: rotate(-5deg);
    }

    .small-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Floating medicine icons */
    .medicine-icon {
        position: absolute;
        font-size: 1.5rem;
        color: rgba(124,179,66,0.6);
        animation: floatMedicine 4s ease-in-out infinite;
        z-index: 3;
    }

    .medicine-icon-1 {
        top: 60px;
        left: 200px;
        animation-delay: 0s;
    }

    .medicine-icon-2 {
        top: 150px;
        right: 60%;
        animation-delay: 1s;
        color: rgba(30,136,229,0.5);
    }

    .medicine-icon-3 {
        bottom: 100px;
        left: 80px;
        animation-delay: 2s;
        color: rgba(239,68,68,0.5);
    }

    .medicine-icon-4 {
        top: 200px;
        left: 300px;
        animation-delay: 3s;
        color: rgba(168,85,247,0.5);
    }

    .medicine-icon-5 {
        bottom: 180px;
        right: 65%;
        animation-delay: 1.5s;
        color: rgba(34,197,94,0.5);
    }

    .medicine-icon-6 {
        top: 80px;
        right: 70%;
        animation-delay: 2.5s;
        color: rgba(251,146,60,0.5);
    }

    @keyframes floatMedicine {
        0%, 100% { 
            transform: translateY(0) rotate(0deg); 
            opacity: 0.6;
        }
        25% { 
            transform: translateY(-10px) rotate(5deg); 
            opacity: 0.8;
        }
        50% { 
            transform: translateY(-15px) rotate(-3deg); 
            opacity: 1;
        }
        75% { 
            transform: translateY(-8px) rotate(2deg); 
            opacity: 0.7;
        }
    }

    /* Decorative elements */
    .hero-decoration {
        position: absolute;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(124,179,66,0.1), rgba(124,179,66,0.3));
        border: 2px solid rgba(124,179,66,0.2);
        z-index: 1;
    }

    .decoration-1 {
        top: 40px;
        right: 65%;
        animation: pulse 3s ease-in-out infinite;
    }

    .decoration-2 {
        bottom: 60px;
        left: 250px;
        width: 40px;
        height: 40px;
        animation: pulse 3s ease-in-out infinite 1s;
    }

    @keyframes pulse {
        0%, 100% { 
            transform: scale(1); 
            opacity: 0.3;
        }
        50% { 
            transform: scale(1.1); 
            opacity: 0.6;
        }
    }

    .photo-card {
        position: absolute;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.35);
        display: none; /* Hide old photo cards */
    }

    .photo-card-back {
        width: 200px;
        height: 240px;
        left: 0;
        top: 60px;
        transform: rotate(-8deg);
        opacity: 0.55;
        z-index: 1;
        display: none;
    }

    .photo-card-mid {
        width: 240px;
        height: 290px;
        right: 20px;
        top: 30px;
        transform: rotate(6deg);
        opacity: 0.75;
        z-index: 2;
        display: none;
    }

    .photo-card-front {
        width: 300px;
        height: 360px;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%) rotate(-2deg);
        z-index: 3;
        display: none;
    }

    .photo-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-card-front .card-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.75), transparent);
        color: white;
        padding: 1.5rem 1rem 1rem;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .float-stat {
        position: absolute;
        background: white;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: floatUp 3s ease-in-out infinite;
    }

    .float-stat-1 { 
        top: 20px; 
        left: 10px; 
        animation-delay: 0s; 
    }
    
    .float-stat-2 { 
        bottom: 40px; 
        left: 200px; 
        animation-delay: 1.5s; 
    }
    
    .float-stat .stat-icon { font-size: 1.5rem; }
    .float-stat .stat-text strong { display: block; font-size: 1rem; font-weight: 700; color: #1f2937; }
    .float-stat .stat-text span { font-size: 0.75rem; color: #6b7280; }

    @keyframes floatUp {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

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
        .hero-section { min-height: 100vh; padding: 5rem 0 3rem; }
        .hero-visual { height: 100%; min-height: 300px; }
        .main-hero-image {
            width: 55%;
            right: -20px;
            top: 0;
            bottom: 0;
            border-radius: 20px 0 0 20px;
            transform: none;
        }
        .main-hero-image:hover { transform: none; }
        .photo-caption { font-size: 0.85rem; padding: 1rem 1rem 0.75rem; }

        /* Foto kecil di mobile */
        .small-photos { display: block; width: 110px; }
        .small-photo-1 { width: 75px; height: 90px; left: 0; top: 10px; transform: rotate(-6deg); }
        .small-photo-2 { width: 65px; height: 78px; left: 45px; top: 130px; transform: rotate(8deg); }
        .small-photo-3 { display: block; width: 60px; height: 72px; left: 5px; top: 250px; transform: rotate(-4deg); }

        /* Icon obat di mobile */
        .medicine-icon { font-size: 1rem; }
        .medicine-icon-1 { top: 40px; left: 110px; }
        .medicine-icon-2 { display: none; }
        .medicine-icon-3 { bottom: 70px; left: 40px; font-size: 0.9rem; }
        .medicine-icon-4 { top: 160px; left: 130px; font-size: 0.9rem; }
        .medicine-icon-5 { display: none; }
        .medicine-icon-6 { display: none; }

        .float-stat { display: none; }
        .hero-decoration { display: none; }
        .hero-dot-grid { display: none; }
        .hero-line-accent { display: none; }
        .hero-title { font-size: clamp(1.8rem, 6vw, 2.5rem); }
        .hero-desc { font-size: 0.95rem; }
        .search-form { flex-direction: column; }
        .medicines-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
    }

    @media (max-width: 480px) {
        .hero-section { padding: 4.5rem 0 3rem; min-height: 100vh; }
        .hero-visual { height: 100%; min-height: 260px; }
        .main-hero-image {
            width: 48%;
            right: -15px;
            top: 0;
            bottom: 0;
            border-radius: 16px 0 0 16px;
        }
        .main-hero-image::before {
            background: linear-gradient(
                105deg,
                rgba(13,71,161,0.85) 0%,
                rgba(21,101,192,0.55) 30%,
                rgba(25,118,210,0.25) 60%,
                transparent 80%
            );
        }
        .photo-caption { display: none; }

        /* Foto kecil di HP kecil */
        .small-photos { display: block; width: 90px; }
        .small-photo-1 { width: 60px; height: 72px; left: 0; top: 8px; transform: rotate(-5deg); }
        .small-photo-2 { width: 52px; height: 62px; left: 35px; top: 110px; transform: rotate(7deg); }
        .small-photo-3 { display: block; width: 48px; height: 58px; left: 5px; top: 210px; transform: rotate(-4deg); }

        /* Icon obat di HP kecil */
        .medicine-icon { font-size: 0.85rem; }
        .medicine-icon-1 { top: 30px; left: 90px; }
        .medicine-icon-2 { display: none; }
        .medicine-icon-3 { bottom: 60px; left: 30px; }
        .medicine-icon-4 { top: 140px; left: 100px; }
        .medicine-icon-5 { display: none; }
        .medicine-icon-6 { display: none; }

        .hero-title { font-size: clamp(1.5rem, 7vw, 1.9rem); }
        .hero-desc { font-size: 0.88rem; max-width: 100%; }
        .hero-buttons { flex-direction: column; gap: 0.75rem; }
        .btn-hero-primary, .btn-hero-outline { text-align: center; justify-content: center; padding: 0.75rem 1.5rem; }
        .hero-badge { font-size: 0.78rem; }
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
        .about-section { padding: 3rem 0; }
        .about-image-main { width: 280px; height: 280px; }
        .about-stats-card { position: static; margin-top: 1.5rem; }
        .feature-item { flex-direction: column; text-align: center; }
        .feature-icon { margin: 0 auto; }
    }
</style>
@endsection

@section('content')

<!-- ===== HERO ===== -->
<section class="hero-section" id="hero-section">
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
