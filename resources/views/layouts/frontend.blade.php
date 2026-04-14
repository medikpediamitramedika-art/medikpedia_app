<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Medikpedia - Apotik Online')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo1.png') }}">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Responsive Fixes -->
    <link rel="stylesheet" href="{{ asset('css/responsive-fixes.css') }}">

    <style>
        /* ===== GLOBAL SECTION DECORATIONS ===== */

        /* Header pages */
        .about-header, .contact-header, .act-header,
        .news-page-header, .products-header, .page-header {
            position: relative;
            overflow: hidden;
        }

        /* Floating deco icons di semua header */
        .header-deco-icon {
            position: absolute;
            color: rgba(255,255,255,0.08);
            pointer-events: none;
            animation: headerIconFloat 6s ease-in-out infinite;
        }
        .header-deco-icon-1 { bottom: 10px; right: 12%; font-size: 4rem;   animation-delay: 0s; }
        .header-deco-icon-2 { top: 15px;   right: 28%; font-size: 3rem;   animation-delay: 2s; }
        .header-deco-icon-3 { bottom: 20px; right: 40%; font-size: 2.5rem; animation-delay: 4s; }

        @keyframes headerIconFloat {
            0%, 100% { transform: translateY(0) rotate(0deg);   opacity: 0.08; }
            50%       { transform: translateY(-12px) rotate(8deg); opacity: 0.15; }
        }

        /* Stats bar */
        .stats-bar { position: relative; overflow: hidden; }
        .stats-bar::before {
            content: '';
            position: absolute;
            top: -40px; left: -40px;
            width: 180px; height: 180px;
            background: radial-gradient(circle, rgba(30,136,229,0.07) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        .stats-bar::after {
            content: '';
            position: absolute;
            bottom: -40px; right: -40px;
            width: 160px; height: 160px;
            background: radial-gradient(circle, rgba(124,179,66,0.07) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }

        /* Products section */
        .products-section { position: relative; overflow: hidden; }
        .products-section::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(30,136,229,0.05) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        .products-section::after {
            content: '';
            position: absolute;
            bottom: 40px; left: -80px;
            width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(124,179,66,0.05) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }

        /* About section */
        .about-section { position: relative; overflow: hidden; }
        .about-section::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(30,136,229,0.04) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        .about-section::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 280px; height: 280px;
            background: radial-gradient(circle, rgba(124,179,66,0.05) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }

        /* About/contact/act main */
        .about-main, .contact-main, .act-main { position: relative; overflow: hidden; }
        .about-main::before, .contact-main::before, .act-main::before {
            content: '';
            position: absolute;
            top: 0; right: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(30,136,229,0.03) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }

        /* Section card subtle deco */
        .section-card { position: relative; overflow: hidden; }
        .section-card::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 120px; height: 120px;
            background: radial-gradient(circle, rgba(30,136,229,0.04) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }

        /* Footer */
        .footer { position: relative; overflow: hidden; }

        /* ===== CARD DECORATIONS ===== */
        .medicine-card, .news-card, .news-preview-card, .photo-card,
        .feature-item, .section-card, .vm-card, .value-item,
        .stat-box, .info-card, .form-card, .detail-container,
        .news-detail-content, .about-image-main, .float-stat {
            position: relative;
            overflow: hidden;
        }

        /* Blob kanan atas - biru */
        .medicine-card::before,
        .news-card::before,
        .news-preview-card::before,
        .feature-item::before,
        .value-item::before,
        .info-card::before,
        .form-card::before,
        .detail-container::before,
        .news-detail-content::before {
            content: '';
            position: absolute;
            top: -25px; right: -25px;
            width: 90px; height: 90px;
            background: radial-gradient(circle, rgba(30,136,229,0.07) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* Blob kiri bawah - hijau */
        .medicine-card::after,
        .news-card::after,
        .news-preview-card::after,
        .photo-card::after,
        .feature-item::after,
        .section-card::after,
        .vm-card::after,
        .stat-box::after,
        .info-card::after,
        .form-card::after,
        .detail-container::after,
        .news-detail-content::after,
        .about-image-main::after,
        .float-stat::after {
            content: '';
            position: absolute;
            bottom: -20px; right: -20px;
            width: 75px; height: 75px;
            background: radial-gradient(circle, rgba(124,179,66,0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* Section card blob lebih besar */
        .section-card::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 120px; height: 120px;
            background: radial-gradient(circle, rgba(30,136,229,0.05) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
    </style>
    
    <style>
        :root {
            --primary: #1E88E5;
            --secondary: #1565C0;
            --accent: #7CB342;
            --dark: #1f2937;
            --light: #f3f4f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(160deg, #dbeafe 0%, #ede9fe 35%, #d1fae5 70%, #dbeafe 100%);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--dark);
        }

        /* Semua konten di atas canvas */
        .navbar, main, footer, .float-wrap {
            position: relative;
            z-index: 1;
        }
        /* Cart dan overlay harus di atas segalanya */
        #cartDrawer, .cart-overlay {
            z-index: 2001 !important;
        }

        /* Global card style — semua card di seluruh halaman */
        .medicine-card, .news-card, .news-preview-card, .feature-card,
        .feature-item, .value-card, .team-card, .related-card,
        .vm-card, .stat-box, .info-card, .form-card,
        .detail-container, .news-detail-content, .filter-bar,
        .news-filter-bar, .farma-sidebar, .disease-card,
        .farma-stat-card, .about-image-main, .about-stats-card,
        .float-stat, .search-box, .empty-state, .cart-footer {
            background: rgba(255,255,255,0.92) !important;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(99,102,241,0.13) !important;
            box-shadow: 0 4px 24px rgba(99,102,241,0.08), 0 1px 4px rgba(0,0,0,0.05) !important;
        }

        /* Section backgrounds */
        .products-main, .farma-main, .act-main,
        .news-main, .about-main, .contact-main,
        .products-section, .features-section,
        .search-section-wrap {
            background: transparent !important;
        }

        .stats-bar, .about-section, .visi-misi-section,
        .team-section, .news-preview-section {
            background: rgba(255,255,255,0.55) !important;
            backdrop-filter: blur(6px);
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #1565C0 0%, #1976D2 50%, #1E88E5 100%);
            box-shadow: 0 4px 20px rgba(13, 71, 161, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            padding: 0.1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 2px solid rgba(124, 179, 66, 0.4);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.3rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.3s;
            flex-shrink: 0;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand img {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
            height: 38px;
            object-fit: contain;
            margin-left: -8px;
        }

        .navbar-menu {
            display: flex;
            gap: 0.15rem;
            align-items: center;
            list-style: none;
            flex-wrap: nowrap;
            justify-content: flex-end;
            margin: 0;
            padding: 0;
        }

        .navbar-menu a,
        .navbar-menu .logout-btn {
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.45rem 0.55rem;
            border-radius: 0.375rem;
            font-size: 0.8rem;
            white-space: nowrap;
            border: none;
            background: none;
            cursor: pointer;
        }

        .navbar-menu a:hover,
        .navbar-menu .logout-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .navbar-menu .logout-btn {
            background: rgba(220, 38, 38, 0.2);
            border: 1px solid rgba(220, 38, 38, 0.4);
            font-size: 0.8rem;
            margin-left: 0.25rem;
        }

        .navbar-menu .logout-btn:hover {
            background: rgba(220, 38, 38, 0.35);
        }

        .navbar-menu .admin-link {
            color: #7CB342;
            font-weight: 600;
        }

        .admin-login-item a {
            padding: 0.45rem 0.55rem;
            font-size: 0.85rem;
            margin-left: 0.25rem;
        }

        /* Cart button di navbar */
        .cart-nav-btn {
            position: relative; 
            background: none; 
            border: none; 
            cursor: pointer;
            color: white; 
            padding: 0.45rem 0.55rem; 
            border-radius: 0.375rem;
            font-size: 0.85rem; 
            display: flex; 
            align-items: center; 
            transition: all 0.3s;
            flex-shrink: 0;
            margin-left: 0.25rem;
        }
        
        .cart-nav-btn:hover { 
            background: rgba(255,255,255,0.15); 
            transform: translateY(-1px);
        }
        
        .cart-badge {
            position: absolute; 
            top: 2px; 
            right: 2px;
            background: #ef4444; 
            color: white; 
            font-size: 0.6rem; 
            font-weight: 800;
            width: 16px; 
            height: 16px; 
            border-radius: 50%;
            display: none; 
            align-items: center; 
            justify-content: center;
        }

        /* Hamburger Menu */
        .hamburger-menu {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            background: none;
            border: none;
            z-index: 101;
            padding: 0.5rem;
        }

        .hamburger-menu span {
            width: 25px;
            height: 3px;
            background: white;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .hamburger-menu.active span:nth-child(1) {
            transform: rotate(45deg) translate(8px, 8px);
        }

        .hamburger-menu.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger-menu.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -7px);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: linear-gradient(180deg, #0f172a 0%, #0a0e2e 100%) !important;
            color: white;
            padding: 4rem 0 0;
            margin-top: 4rem;
            position: relative;
            z-index: 1;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr 1.4fr;
            gap: 2.5rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        /* Kolom brand */
        .footer-brand .footer-logo {
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .footer-brand p {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.75;
            margin-bottom: 1.25rem;
        }

        .footer-socials {
            display: flex;
            gap: 0.6rem;
        }

        .footer-social-btn {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.08);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.2s;
            border: 1px solid rgba(255,255,255,0.06);
        }

        .footer-social-btn:hover {
            color: white;
            transform: translateY(-2px);
        }

        /* Kolom navigasi */
        .footer-col h4 {
            font-size: 0.8rem;
            font-weight: 700;
            color: #7CB342;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 1.1rem;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-col ul li {
            margin-bottom: 0.6rem;
        }

        .footer-col ul a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s;
        }

        .footer-col ul a i {
            color: #7CB342;
            font-size: 0.75rem;
            width: 14px;
            flex-shrink: 0;
        }

        .footer-col ul a:hover {
            color: white;
        }

        /* Kolom kontak */
        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            margin-bottom: 0.85rem;
        }

        .footer-contact-item i {
            color: #7CB342;
            font-size: 0.85rem;
            margin-top: 3px;
            flex-shrink: 0;
            width: 14px;
        }

        .footer-contact-item a,
        .footer-contact-item span {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            line-height: 1.6;
            transition: color 0.2s;
        }

        .footer-contact-item a:hover { color: white; }

        /* Footer bottom */
        .footer-bottom {
            padding: 1.25rem 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .footer-bottom p {
            color: #64748b;
            font-size: 0.8rem;
            margin: 0;
        }

        .footer-bottom-links {
            display: flex;
            gap: 1.25rem;
        }

        .footer-bottom-links a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.8rem;
            transition: color 0.2s;
        }

        .footer-bottom-links a:hover { color: #94a3b8; }

        @media (max-width: 1024px) {
            /* Navbar tablet fixes */
            .navbar-container {
                padding: 0.25rem 1rem;
            }

            .navbar-brand img {
                height: 34px;
            }

            .navbar-menu a,
            .navbar-menu .logout-btn {
                font-size: 0.75rem;
                padding: 0.4rem 0.45rem;
                gap: 0.25rem;
            }

            .navbar-menu {
                gap: 0.1rem;
            }

            .admin-login-item a {
                padding: 0.4rem 0.45rem;
                font-size: 0.8rem;
            }

            .cart-nav-btn {
                padding: 0.4rem 0.45rem;
                font-size: 0.8rem;
            }

            /* Footer */
            .footer-content {
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
            }
        }

        @media (max-width: 640px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 1.75rem;
                padding-bottom: 2rem;
            }
            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }

        /* Alert */
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid var(--primary);
        }

        .alert-error {
            background: #fee2e2;
            color: #7f1d1d;
            border-left: 4px solid #ef4444;
        }

        @media (max-width: 768px) {
            .hamburger-menu {
                display: flex;
            }

            .navbar-container {
                padding: 0.25rem 1rem;
                gap: 0.5rem;
            }

            .navbar-brand img {
                height: 34px;
            }

            .navbar-menu {
                position: fixed;
                left: 0;
                top: 52px;
                width: 100%;
                height: calc(100vh - 52px);
                background: linear-gradient(135deg, #1565C0 0%, #1976D2 50%, #1E88E5 100%);
                flex-direction: column;
                justify-content: flex-start;
                gap: 0;
                padding: 1rem;
                max-height: 0;
                overflow-y: auto;
                overflow-x: hidden;
                transition: max-height 0.35s ease;
                z-index: 100;
            }

            .navbar-menu.active {
                max-height: calc(100vh - 52px);
            }

            .navbar-menu li {
                width: 100%;
                margin-bottom: 0.25rem;
            }

            .navbar-menu a,
            .navbar-menu .logout-btn {
                padding: 0.8rem 1rem;
                font-size: 0.95rem;
                display: flex;
                width: 100%;
                border-radius: 8px;
                justify-content: flex-start;
                gap: 0.5rem;
            }

            .navbar-menu .logout-btn {
                margin-left: 0;
                margin-top: 0.5rem;
                border: 1px solid rgba(220, 38, 38, 0.4);
            }

            .admin-login-item a {
                margin-left: 0;
                padding: 0.8rem 1rem;
                font-size: 0.95rem;
            }

            .cart-nav-btn {
                margin-left: 0;
                padding: 0.45rem;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            .navbar-brand img {
                height: 30px;
            }

            .navbar-container {
                padding: 0.2rem 0.75rem;
            }

            .navbar-menu {
                top: 48px;
                height: calc(100vh - 48px);
                padding: 0.75rem;
            }

            .navbar-menu.active {
                max-height: calc(100vh - 48px);
            }

            .navbar-menu a,
            .navbar-menu .logout-btn {
                padding: 0.75rem 0.875rem;
                font-size: 0.9rem;
            }

            .cart-nav-btn {
                padding: 0.4rem;
                font-size: 0.9rem;
            }
        }
    </style>

    @yield('styles')
</head>
<body>

    <!-- ===== PARTIKEL STATIS MEDIS ===== -->
    <div class="med-particles" aria-hidden="true">
        <!-- Kapsul -->
        <span class="mp mp-capsule" style="top:4%;left:3%;--r:15deg;--s:0.9;--o:0.30;"></span>
        <span class="mp mp-capsule" style="top:18%;left:88%;--r:-20deg;--s:1.1;--o:0.28;"></span>
        <span class="mp mp-capsule" style="top:35%;left:12%;--r:30deg;--s:0.8;--o:0.32;"></span>
        <span class="mp mp-capsule" style="top:55%;left:92%;--r:-10deg;--s:1.0;--o:0.27;"></span>
        <span class="mp mp-capsule" style="top:72%;left:5%;--r:25deg;--s:0.95;--o:0.30;"></span>
        <span class="mp mp-capsule" style="top:88%;left:78%;--r:-35deg;--s:0.85;--o:0.28;"></span>
        <span class="mp mp-capsule" style="top:45%;left:50%;--r:12deg;--s:0.75;--o:0.25;"></span>
        <span class="mp mp-capsule" style="top:62%;left:35%;--r:-18deg;--s:1.05;--o:0.27;"></span>

        <!-- Tablet bulat -->
        <span class="mp mp-tablet" style="top:8%;left:22%;--s:1.0;--o:0.30;"></span>
        <span class="mp mp-tablet" style="top:28%;left:68%;--s:1.2;--o:0.27;"></span>
        <span class="mp mp-tablet" style="top:50%;left:80%;--s:0.9;--o:0.29;"></span>
        <span class="mp mp-tablet" style="top:78%;left:42%;--s:1.1;--o:0.28;"></span>
        <span class="mp mp-tablet" style="top:92%;left:15%;--s:0.85;--o:0.26;"></span>
        <span class="mp mp-tablet" style="top:15%;left:55%;--s:0.95;--o:0.27;"></span>

        <!-- Tanda plus / palang medis -->
        <span class="mp mp-cross" style="top:6%;left:45%;--s:1.1;--o:0.32;"></span>
        <span class="mp mp-cross" style="top:22%;left:8%;--s:0.9;--o:0.30;"></span>
        <span class="mp mp-cross" style="top:40%;left:95%;--s:1.0;--o:0.28;"></span>
        <span class="mp mp-cross" style="top:60%;left:60%;--s:1.2;--o:0.30;"></span>
        <span class="mp mp-cross" style="top:80%;left:25%;--s:0.95;--o:0.32;"></span>
        <span class="mp mp-cross" style="top:95%;left:55%;--s:0.85;--o:0.27;"></span>
        <span class="mp mp-cross" style="top:33%;left:38%;--s:0.8;--o:0.26;"></span>

        <!-- Pil kecil bulat -->
        <span class="mp mp-pill" style="top:12%;left:75%;--s:1.1;--o:0.33;"></span>
        <span class="mp mp-pill" style="top:30%;left:28%;--s:1.3;--o:0.30;"></span>
        <span class="mp mp-pill" style="top:48%;left:18%;--s:1.0;--o:0.28;"></span>
        <span class="mp mp-pill" style="top:65%;left:85%;--s:0.9;--o:0.31;"></span>
        <span class="mp mp-pill" style="top:82%;left:62%;--s:1.2;--o:0.29;"></span>
        <span class="mp mp-pill" style="top:96%;left:38%;--s:1.05;--o:0.27;"></span>
        <span class="mp mp-pill" style="top:20%;left:42%;--s:0.8;--o:0.26;"></span>

        <!-- Ikon FA medis -->
        <i class="mp mp-icon fa-solid fa-pills"         style="top:10%;left:60%;--s:1.3;--o:0.28;"></i>
        <i class="mp mp-icon fa-solid fa-capsules"      style="top:25%;left:82%;--s:1.1;--o:0.27;"></i>
        <i class="mp mp-icon fa-solid fa-syringe"       style="top:42%;left:6%;--r:45deg;--s:1.2;--o:0.27;"></i>
        <i class="mp mp-icon fa-solid fa-stethoscope"   style="top:58%;left:72%;--s:1.05;--o:0.26;"></i>
        <i class="mp mp-icon fa-solid fa-heart-pulse"   style="top:75%;left:15%;--s:1.2;--o:0.28;"></i>
        <i class="mp mp-icon fa-solid fa-flask"         style="top:88%;left:90%;--s:1.1;--o:0.27;"></i>
        <i class="mp mp-icon fa-solid fa-dna"           style="top:5%;left:35%;--s:1.3;--o:0.26;"></i>
        <i class="mp mp-icon fa-solid fa-microscope"    style="top:38%;left:55%;--s:1.0;--o:0.25;"></i>
        <i class="mp mp-icon fa-solid fa-tablets"       style="top:68%;left:48%;--s:1.1;--o:0.27;"></i>
        <i class="mp mp-icon fa-solid fa-virus"         style="top:85%;left:8%;--s:0.95;--o:0.25;"></i>
    </div>

    <style>
        /* ===== PARTIKEL STATIS ===== */
        .med-particles {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        /* Base semua partikel */
        .mp {
            position: absolute;
            transform: rotate(var(--r, 0deg)) scale(var(--s, 1));
            opacity: var(--o, 0.25);
        }

        /* Kapsul dua warna */
        .mp-capsule {
            width: 60px; height: 26px;
            border-radius: 13px;
            background: linear-gradient(90deg, #1E88E5 50%, #7CB342 50%);
            box-shadow: 0 2px 8px rgba(30,136,229,0.25), inset 0 1px 3px rgba(255,255,255,0.4);
        }

        /* Tablet oval */
        .mp-tablet {
            width: 42px; height: 26px;
            border-radius: 13px;
            background: #bbdefb;
            border: 2.5px solid #42a5f5;
            position: relative;
            box-shadow: 0 2px 8px rgba(66,165,245,0.2);
        }
        .mp-tablet::after {
            content: '';
            position: absolute;
            top: 50%; left: 18%; right: 18%;
            height: 2px;
            background: #42a5f5;
            transform: translateY(-50%);
            border-radius: 1px;
        }

        /* Pil bulat */
        .mp-pill {
            width: 24px; height: 24px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, #ffffff, #66bb6a);
            border: 2px solid rgba(102,187,106,0.6);
            box-shadow: 0 2px 8px rgba(124,179,66,0.25);
        }

        /* Tanda plus medis */
        .mp-cross {
            width: 28px; height: 28px;
            position: relative;
            filter: drop-shadow(0 2px 4px rgba(30,136,229,0.3));
        }
        .mp-cross::before,
        .mp-cross::after {
            content: '';
            position: absolute;
            background: #1E88E5;
            border-radius: 3px;
        }
        .mp-cross::before {
            width: 10px; height: 28px;
            left: 50%; transform: translateX(-50%);
        }
        .mp-cross::after {
            width: 28px; height: 10px;
            top: 50%; transform: translateY(-50%);
        }

        /* Ikon Font Awesome */
        .mp-icon {
            font-size: 1.6rem;
            color: #1565C0;
            transform: rotate(var(--r, 0deg)) scale(var(--s, 1));
            filter: drop-shadow(0 2px 4px rgba(21,101,192,0.2));
        }

        /* Variasi warna ikon */
        .mp-icon:nth-child(odd)  { color: #388e3c; }
        .mp-icon:nth-child(3n)   { color: #0288d1; }
        .mp-icon:nth-child(4n)   { color: #1976d2; }
    </style>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="{{ asset('logo2.png') }}" alt="Medikpedia">
            </a>
            
            <!-- Hamburger Menu Button -->
            <button class="hamburger-menu" id="hamburgerBtn">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <ul class="navbar-menu" id="navbarMenu">
                <li><a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="{{ route('products') }}"><i class="fa-solid fa-pills"></i> Produk</a></li>
                <li><a href="{{ route('news.index') }}"><i class="fa-solid fa-tag"></i> Produk Promo</a></li>
                <li><a href="{{ route('activities.index') }}"><i class="fa-solid fa-camera"></i> Aktivitas</a></li>
                <li><a href="{{ route('farmakologi') }}"><i class="fa-solid fa-book-medical"></i> Farmakologi</a></li>
                <li><a href="{{ route('about') }}"><i class="fa-solid fa-circle-info"></i> Tentang Kami</a></li>
                <li><a href="{{ route('contact') }}"><i class="fa-solid fa-headset"></i> Hubungi Kami</a></li>
                
                @auth
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="admin-link"><i class="fa-solid fa-gauge"></i> Admin Panel</a></li>
                    @endif
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                        </form>
                    </li>
                @else
                    <li class="admin-login-item">
                        <a href="{{ route('login') }}" title="Admin Login"><i class="fa-solid fa-user-shield"></i></a>
                    </li>
                @endauth
            </ul>

            {{-- Cart button --}}
            <button class="cart-nav-btn" id="cartNavBtn" onclick="window.location.href='{{ route('products') }}#keranjang'" title="Keranjang Belanja" style="display:none;">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-badge" id="cartBadgeNav">0</span>
            </button>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if ($message = Session::get('success'))
        <div class="container">
            <div class="alert alert-success">
                {{ $message }}
            </div>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="container">
            <div class="alert alert-error">
                {{ $message }}
            </div>
        </div>
    @endif

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">

                {{-- Kolom Brand --}}
                <div class="footer-brand">
                    <div class="footer-logo">
                        <i class="fa-solid fa-pills" style="color:#7CB342;"></i>
                        <span><span style="color:#1E88E5;">Medik</span><span style="color:#7CB342;">pedia</span></span>
                    </div>
                    <p>Distributor farmasi dan apotik online terpercaya. Produk original, harga kompetitif, pengiriman ke seluruh Indonesia.</p>
                    <div class="footer-socials">
                        <a href="#" class="footer-social-btn" title="Facebook" style="--hover:#1877f2;" onmouseover="this.style.background='#1877f2';this.style.color='white';this.style.borderColor='#1877f2'" onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='#cbd5e1';this.style.borderColor='rgba(255,255,255,0.06)'">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="footer-social-btn" title="Instagram" onmouseover="this.style.background='#e1306c';this.style.color='white';this.style.borderColor='#e1306c'" onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='#cbd5e1';this.style.borderColor='rgba(255,255,255,0.06)'">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="https://www.tiktok.com/@medikpedia" target="_blank" class="footer-social-btn" title="TikTok" onmouseover="this.style.background='#010101';this.style.color='white';this.style.borderColor='#010101'" onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='#cbd5e1';this.style.borderColor='rgba(255,255,255,0.06)'">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                        <a href="https://wa.me/6285890007359" target="_blank" class="footer-social-btn" title="WhatsApp" onmouseover="this.style.background='#25d366';this.style.color='white';this.style.borderColor='#25d366'" onmouseout="this.style.background='rgba(255,255,255,0.08)';this.style.color='#cbd5e1';this.style.borderColor='rgba(255,255,255,0.06)'">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                {{-- Kolom Produk --}}
                <div class="footer-col">
                    <h4>Produk</h4>
                    <ul>
                        <li><a href="{{ route('products') }}"><i class="fa-solid fa-capsules"></i> Katalog Obat</a></li>
                        <li><a href="{{ route('news.index') }}"><i class="fa-solid fa-tag"></i> Produk Promo</a></li>
                        <li><a href="{{ route('farmakologi') }}"><i class="fa-solid fa-book-medical"></i> Farmakologi</a></li>
                        <li><a href="{{ route('activities.index') }}"><i class="fa-solid fa-camera"></i> Aktivitas</a></li>
                    </ul>
                </div>

                {{-- Kolom Informasi --}}
                <div class="footer-col">
                    <h4>Informasi</h4>
                    <ul>
                        <li><a href="{{ route('about') }}"><i class="fa-solid fa-building"></i> Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fa-solid fa-headset"></i> Hubungi Kami</a></li>
                        <li><a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Beranda</a></li>
                        <li><a href="#"><i class="fa-solid fa-shield-halved"></i> Kebijakan Privasi</a></li>
                    </ul>
                </div>

                {{-- Kolom Kontak --}}
                <div class="footer-col">
                    <h4>Kontak Kami</h4>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-phone"></i>
                        <a href="tel:+6285890007359">0858 9000 7359</a>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-brands fa-whatsapp"></i>
                        <a href="https://wa.me/6285890007359" target="_blank">Chat WhatsApp</a>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-envelope"></i>
                        <a href="mailto:medikpedia.mitramedika@gmail.com">medikpedia.mitramedika<br>@gmail.com</a>
                    </div>
                    <div class="footer-contact-item">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Jl. Letjen Suprapto No.1, Kemayoran, Jakarta Pusat 10640</span>
                    </div>
                </div>

            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Medikpedia. Semua hak dilindungi.</p>
                <div class="footer-bottom-links">
                    <a href="#">Syarat & Ketentuan</a>
                    <a href="#">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Buttons -->
    <style>
        .float-wrap { position:fixed; bottom:1.75rem; right:1.75rem; display:flex; flex-direction:column; align-items:center; gap:0; z-index:999; }

        /* Links container desktop */
        .float-links {
            display:flex; flex-direction:column; align-items:center; gap:1.6rem;
        }

        .float-item { position:relative; display:flex; align-items:center; justify-content:center; }
        .float-tooltip { display:none; }
        .float-label-mobile { display:none; }

        .float-btn {
            width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center;
            text-decoration:none; transition:transform 0.2s, box-shadow 0.2s; flex-shrink:0;
            font-size:1.1rem;
        }
        .float-btn:hover { transform:scale(1.13); }

        /* WhatsApp lebih besar */
        .float-btn-wa {
            width:60px !important; height:60px !important; font-size:1.85rem !important;
            margin-top:0.6rem;
        }

        /* Toggle button (mobile only) */
        .float-toggle {
            width:50px; height:50px; border-radius:50%; background:linear-gradient(135deg,#1565C0,#1E88E5);
            border:none; color:white; font-size:1.3rem; cursor:pointer;
            display:none; align-items:center; justify-content:center;
            box-shadow:0 4px 16px rgba(13,71,161,0.45); transition:transform 0.3s;
            flex-shrink:0;
        }
        .float-toggle.open { transform:rotate(45deg); }

        /* Mobile */
        @media (max-width: 768px) {
            .float-links {
                display:flex; flex-direction:column; align-items:flex-end; gap:0.75rem;
                overflow:hidden; max-height:0; transition:max-height 0.4s ease, opacity 0.3s;
                opacity:0;
            }
            .float-links.open { max-height:500px; opacity:1; }
            .float-wrap { gap:0.6rem; align-items:flex-end; }
            .float-btn { width:50px !important; height:50px !important; font-size:1.4rem !important; }
            .float-btn-wa { width:50px !important; height:50px !important; font-size:1.6rem !important; margin-top:0; }
            .float-toggle { display:flex; }
            .float-item { gap:0.5rem; }
            .float-label-mobile {
                background:#1f2937; color:white; font-size:0.72rem; font-weight:600;
                padding:0.25rem 0.6rem; border-radius:8px; white-space:nowrap;
                display:none;
            }
            .float-links.open .float-label-mobile { display:block; }
        }
    </style>

    <div class="float-wrap">
        <!-- Links (semua tombol) -->
        <div class="float-links" id="floatLinks">
            <!-- Dermilosofi -->
            <div class="float-item">
                <span class="float-tooltip">Dermilosofi</span>
                <span class="float-label-mobile">Dermilosofi</span>
                <a href="https://dermilosofi.site" target="_blank" class="float-btn"
                   style="background:#fff;box-shadow:0 4px 16px rgba(0,0,0,0.2);overflow:hidden;">
                    <img src="{{ asset('logodermilosofi.site.jpeg') }}" alt="Dermilosofi" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                </a>
            </div>
            <!-- Shopee -->
            <div class="float-item">
                <span class="float-tooltip">Shopee</span>
                <span class="float-label-mobile">Shopee</span>
                <a href="https://shopee.co.id/medikpedia.mitramedika" target="_blank" class="float-btn"
                   style="background:#ee4d2d;box-shadow:0 4px 16px rgba(238,77,45,0.45);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="white">
                        <path d="M12 2a4 4 0 0 0-4 4H5l-1 15h16l-1-15h-3a4 4 0 0 0-4-4zm0 1.5a2.5 2.5 0 0 1 2.5 2.5h-5A2.5 2.5 0 0 1 12 3.5z"/>
                        <text x="12" y="17" text-anchor="middle" font-size="7.5" font-weight="900" font-family="Arial,sans-serif" fill="#ee4d2d">S</text>
                    </svg>
                </a>
            </div>
            <!-- TikTok -->
            <div class="float-item">
                <span class="float-tooltip">TikTok</span>
                <span class="float-label-mobile">TikTok</span>
                <a href="https://www.tiktok.com/@medikpedia" target="_blank" class="float-btn"
                   style="background:#010101;color:white;font-size:1.4rem;box-shadow:0 4px 16px rgba(0,0,0,0.35);">
                    <i class="fa-brands fa-tiktok"></i>
                </a>
            </div>
            <!-- Instagram -->
            <div class="float-item">
                <span class="float-tooltip">Instagram</span>
                <span class="float-label-mobile">Instagram</span>
                <a href="https://instagram.com" target="_blank" class="float-btn"
                   style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:white;font-size:1.4rem;box-shadow:0 4px 16px rgba(220,39,67,0.45);">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </div>
            <!-- WhatsApp -->
            <div class="float-item">
                <span class="float-tooltip">Chat WhatsApp</span>
                <span class="float-label-mobile">WhatsApp</span>
                <a href="https://wa.me/6285890007359?text=Halo%20Medikpedia%2C%20saya%20ingin%20bertanya%20tentang%20produk%20obat."
                   target="_blank" class="float-btn float-btn-wa"
                   style="background:#25D366;color:white;font-size:1.9rem;box-shadow:0 6px 24px rgba(37,211,102,0.55);">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>
        </div>

        <!-- Toggle button (mobile only) -->
        <button class="float-toggle" id="floatToggle" onclick="toggleFloat()">
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    <script>
        function toggleFloat() {
            const links  = document.getElementById('floatLinks');
            const toggle = document.getElementById('floatToggle');
            links.classList.toggle('open');
            toggle.classList.toggle('open');
        }
        // Desktop: selalu tampil
        function checkFloatDesktop() {
            const links = document.getElementById('floatLinks');
            if (window.innerWidth > 768) {
                links.style.maxHeight = '';
                links.style.opacity   = '';
                links.style.overflow  = '';
                links.style.display   = 'flex';
            } else {
                if (!links.classList.contains('open')) {
                    links.style.maxHeight = '0';
                    links.style.opacity   = '0';
                }
            }
        }
        checkFloatDesktop();
        window.addEventListener('resize', checkFloatDesktop);
    </script>

    @yield('scripts')

    <script>
        // ===== SMOOTH SCROLL =====
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // ===== SCROLL REVEAL =====
        const revealStyle = document.createElement('style');
        revealStyle.textContent = `
            .reveal {
                opacity: 0;
                transform: translateY(32px);
                transition: opacity 0.6s ease, transform 0.6s ease;
            }
            .reveal.visible {
                opacity: 1;
                transform: translateY(0);
            }
            .reveal-left {
                opacity: 0;
                transform: translateX(-40px);
                transition: opacity 0.6s ease, transform 0.6s ease;
            }
            .reveal-left.visible {
                opacity: 1;
                transform: translateX(0);
            }
            .reveal-right {
                opacity: 0;
                transform: translateX(40px);
                transition: opacity 0.6s ease, transform 0.6s ease;
            }
            .reveal-right.visible {
                opacity: 1;
                transform: translateX(0);
            }
            .reveal-scale {
                opacity: 0;
                transform: scale(0.92);
                transition: opacity 0.55s ease, transform 0.55s ease;
            }
            .reveal-scale.visible {
                opacity: 1;
                transform: scale(1);
            }
        `;
        document.head.appendChild(revealStyle);

        // Auto-tag elemen yang perlu dianimasikan
        function tagRevealElements() {
            const selectors = [
                // Cards
                '.feature-card', '.medicine-card', '.news-preview-card',
                '.news-card', '.value-card', '.team-card', '.related-card',
                '.vm-card', '.stat-card',
                // Sections & blocks
                '.about-section', '.about-text', '.about-image-stack',
                '.price-section', '.description-section',
                '.detail-container > .detail-grid',
                '.related-section',
                // Stats bar items
                '.stat-item',
                // Footer columns
                '.footer-content > div',
            ];

            selectors.forEach(sel => {
                document.querySelectorAll(sel).forEach((el, i) => {
                    if (!el.classList.contains('reveal') &&
                        !el.classList.contains('reveal-left') &&
                        !el.classList.contains('reveal-right') &&
                        !el.classList.contains('reveal-scale')) {
                        el.classList.add('reveal');
                        // Stagger delay untuk grid items
                        el.style.transitionDelay = (i % 4) * 0.1 + 's';
                    }
                });
            });

            // Kolom kiri/kanan di row Bootstrap
            document.querySelectorAll('.row > .col-lg-5, .row > .col-md-5').forEach(el => {
                if (!el.querySelector('.reveal-left') && !el.classList.contains('reveal-left')) {
                    el.classList.add('reveal-left');
                }
            });
            document.querySelectorAll('.row > .col-lg-7, .row > .col-md-7').forEach(el => {
                if (!el.classList.contains('reveal-right')) {
                    el.classList.add('reveal-right');
                }
            });
        }

        // IntersectionObserver untuk trigger animasi
        function initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

            document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale')
                .forEach(el => observer.observe(el));
        }

        // Jalankan setelah DOM siap
        document.addEventListener('DOMContentLoaded', () => {
            tagRevealElements();
            initObserver();
        });

        // Fallback jika DOMContentLoaded sudah lewat
        if (document.readyState !== 'loading') {
            tagRevealElements();
            initObserver();
        }
    </script>

    <script>
        // Cart badge sync dari localStorage (semua halaman)
        (function() {
            const cart = JSON.parse(localStorage.getItem('medikpedia_cart') || '[]');
            const total = cart.reduce((s, i) => s + i.qty, 0);
            const badge = document.getElementById('cartBadgeNav');
            const btn   = document.getElementById('cartNavBtn');
            if (badge && total > 0) {
                badge.textContent = total;
                badge.style.display = 'flex';
            }
            // Tampilkan tombol cart di semua halaman jika ada isi keranjang
            if (btn && total > 0) btn.style.display = 'flex';
        })();

        // Hamburger Menu Toggle
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const navbarMenu = document.getElementById('navbarMenu');

        hamburgerBtn.addEventListener('click', () => {
            hamburgerBtn.classList.toggle('active');
            navbarMenu.classList.toggle('active');
        });

        // Close menu when clicking on a link
        const menuLinks = navbarMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburgerBtn.classList.remove('active');
                navbarMenu.classList.remove('active');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.navbar')) {
                hamburgerBtn.classList.remove('active');
                navbarMenu.classList.remove('active');
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>