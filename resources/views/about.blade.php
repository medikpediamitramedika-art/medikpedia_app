@extends('layouts.frontend')

@section('title', 'Tentang Kami - Medikpedia')

@section('styles')
<style>
    .about-header {
        background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1E88E5 100%);
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
    }
    .about-header::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(124,179,66,0.18) 0%, transparent 70%);
        border-radius: 50%;
    }
    .about-header .header-deco-icon {
        position: absolute;
        color: rgba(255,255,255,0.08);
        pointer-events: none;
        animation: headerIconFloat 6s ease-in-out infinite;
    }
    .about-header .header-deco-icon-1 { bottom: 10px; right: 12%; font-size: 4rem; animation-delay: 0s; }
    .about-header .header-deco-icon-2 { top: 15px;   right: 28%; font-size: 3rem; animation-delay: 2s; }
    .about-header .header-deco-icon-3 { bottom: 20px; right: 40%; font-size: 2.5rem; animation-delay: 4s; }
    @keyframes headerIconFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.08; }
        50%       { transform: translateY(-12px) rotate(8deg); opacity: 0.14; }
    }
    .about-header h1 { font-size: clamp(2rem,4vw,3rem); font-weight: 800; color: white; margin-bottom: 0.5rem; position: relative; }
    .about-header p  { color: rgba(255,255,255,0.8); font-size: 1rem; position: relative; }
    .breadcrumb-custom { display: flex; gap: 0.5rem; align-items: center; margin-bottom: 1rem; position: relative; }
    .breadcrumb-custom a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; }
    .breadcrumb-custom a:hover { color: white; }
    .breadcrumb-custom span { color: rgba(255,255,255,0.5); font-size: 0.9rem; }
    .breadcrumb-custom .current { color: #a5d65a; font-size: 0.9rem; font-weight: 600; }

    .about-main { background: #f8faff; padding: 3rem 0 5rem; }

    .section-card {
        background: white;
        border-radius: 16px;
        padding: 2rem 2.5rem;
        margin-bottom: 1.75rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
    }

    .section-card h2 {
        font-size: 1.2rem; font-weight: 700; color: #1f2937;
        margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem;
    }

    .section-card h2 i { color: #1E88E5; }

    .section-card p { color: #4b5563; line-height: 1.8; font-size: 0.95rem; margin-bottom: 0.75rem; }
    .section-card p:last-child { margin-bottom: 0; }

    /* Stats */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-top: 1.25rem;
    }
    .stat-box {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
    }
    .stat-box .num { font-size: 1.75rem; font-weight: 800; color: #1565C0; display: block; }
    .stat-box .lbl { font-size: 0.8rem; color: #374151; font-weight: 600; }

    /* VM Cards */
    .vm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .vm-card { border-radius: 12px; padding: 1.5rem; }
    .vm-card-visi { background: linear-gradient(135deg, #e3f2fd, #bbdefb); border: 1px solid #90caf9; }
    .vm-card-misi { background: linear-gradient(135deg, #f1f8e9, #dcedc8); border: 1px solid #aed581; }
    .vm-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 0.75rem; }
    .vm-card-visi h3 { color: #1565C0; }
    .vm-card-misi h3 { color: #558B2F; }
    .vm-card p { font-size: 0.9rem; color: #374151; line-height: 1.7; margin: 0; }

    /* Values */
    .values-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-top: 0.5rem; }
    .value-item { text-align: center; padding: 1.25rem; background: #f8faff; border-radius: 12px; border: 1px solid #e5e7eb; }
    .value-item i { font-size: 1.75rem; margin-bottom: 0.6rem; display: block; }
    .value-item h4 { font-size: 0.9rem; font-weight: 700; color: #1f2937; margin-bottom: 0.4rem; }
    .value-item p { font-size: 0.8rem; color: #6b7280; margin: 0; line-height: 1.6; }

    /* Checklist */
    .check-list { list-style: none; padding: 0; margin: 0.75rem 0 0; }
    .check-list li { display: flex; align-items: flex-start; gap: 0.6rem; margin-bottom: 0.6rem; font-size: 0.9rem; color: #374151; }
    .check-list li i { color: #7CB342; margin-top: 2px; flex-shrink: 0; }

    /* CTA */
    .cta-box {
        background: linear-gradient(135deg, #0D47A1, #1E88E5);
        border-radius: 16px; padding: 2rem 2.5rem;
        text-align: center; color: white;
    }
    .cta-box h3 { font-size: 1.3rem; font-weight: 700; margin-bottom: 0.5rem; }
    .cta-box p  { color: rgba(255,255,255,0.85); font-size: 0.95rem; margin-bottom: 1.25rem; }
    .cta-btn {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: #25D366; color: white; padding: 0.75rem 1.75rem;
        border-radius: 50px; text-decoration: none; font-weight: 700;
        font-size: 0.95rem; transition: all 0.3s;
    }
    .cta-btn:hover { background: #1ebe5d; transform: translateY(-2px); color: white; box-shadow: 0 6px 20px rgba(37,211,102,0.4); }

    @media (max-width: 640px) {
        .vm-grid { grid-template-columns: 1fr; }
        .section-card { padding: 1.5rem; }
    }
</style>
@endsection

@section('content')

<div class="about-header">
    <div class="container">
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            <span>/</span>
            <span class="current">Tentang Kami</span>
        </div>
        <h1><i class="fa-solid fa-building-columns"></i> Tentang Medikpedia</h1>
        <p>Apotik online terpercaya untuk kebutuhan kesehatan Anda</p>
    </div>
    <i class="fa-solid fa-pills header-deco-icon header-deco-icon-1"></i>
    <i class="fa-solid fa-stethoscope header-deco-icon header-deco-icon-2"></i>
    <i class="fa-solid fa-heart-pulse header-deco-icon header-deco-icon-3"></i>
</div>

<div class="about-main">
    <div class="container" style="max-width: 860px;">

        {{-- Profil Perusahaan --}}
        <div class="section-card">
            <h2><i class="fa-solid fa-hospital"></i> Profil Perusahaan</h2>
            <p>
                <strong>Medikpedia</strong> adalah perusahaan distributor farmasi dan apotik online terpercaya yang telah berpengalaman lebih dari 15 tahun dalam melayani kebutuhan kesehatan masyarakat Indonesia. Kami berkomitmen menyediakan obat-obatan berkualitas tinggi dengan harga terjangkau dan layanan terbaik.
            </p>
            <p>
                Sebagai <strong>distributor resmi</strong> berbagai brand farmasi ternama, kami memiliki izin lengkap dari BPOM dan Kementerian Kesehatan. Berlokasi strategis di <strong>Jl. Letjen Suprapto No.1, Kemayoran, Jakarta Pusat</strong>, kami melayani distribusi ke seluruh Indonesia dengan jaringan yang luas dan sistem logistik yang handal.
            </p>
            <p>
                Melalui platform <strong>apotik online</strong> yang modern dan user-friendly, kami menyediakan akses mudah ke ribuan produk kesehatan dengan konsultasi gratis dari apoteker berpengalaman dan pengiriman cepat ke seluruh nusantara.
            </p>
            <div class="stats-row">
                <div class="stat-box">
                    <span class="num">10K+</span>
                    <span class="lbl">Pelanggan Puas</span>
                </div>
                <div class="stat-box">
                    <span class="num">500+</span>
                    <span class="lbl">Produk Tersedia</span>
                </div>
                <div class="stat-box">
                    <span class="num">100+</span>
                    <span class="lbl">Brand Partner</span>
                </div>
                <div class="stat-box">
                    <span class="num">15+</span>
                    <span class="lbl">Tahun Pengalaman</span>
                </div>
            </div>
        </div>

        {{-- Visi & Misi --}}
        <div class="section-card">
            <h2><i class="fa-solid fa-bullseye"></i> Visi & Misi</h2>
            <div class="vm-grid">
                <div class="vm-card vm-card-visi">
                    <h3><i class="fa-solid fa-eye"></i> Visi</h3>
                    <p>Menjadi distributor farmasi dan apotik online terpercaya yang terdepan dalam memberikan solusi kesehatan berkualitas, terjangkau, dan mudah diakses untuk seluruh masyarakat Indonesia.</p>
                </div>
                <div class="vm-card vm-card-misi">
                    <h3><i class="fa-solid fa-rocket"></i> Misi</h3>
                    <p>Mendistribusikan produk farmasi berkualitas tinggi melalui jaringan yang luas, menyediakan layanan apotik online 24/7 dengan konsultasi profesional, dan memastikan akses kesehatan yang merata di seluruh Indonesia.</p>
                </div>
            </div>
        </div>

        {{-- Keunggulan --}}
        <div class="section-card">
            <h2><i class="fa-solid fa-star"></i> Mengapa Memilih Kami?</h2>
            <ul class="check-list">
                <li><i class="fa-solid fa-circle-check"></i> <strong>Distributor Resmi:</strong> Izin lengkap BPOM & Kementerian Kesehatan</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Produk Original:</strong> Semua produk tersertifikasi dan berstandar GMP</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Jaringan Luas:</strong> Distribusi ke 50+ kota di seluruh Indonesia</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Harga Kompetitif:</strong> Langsung dari distributor tanpa markup berlebih</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Apotik Online 24/7:</strong> Layanan konsultasi dan pemesanan sepanjang waktu</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Pengiriman Cepat:</strong> Sistem logistik handal dengan cold chain untuk produk khusus</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Konsultasi Profesional:</strong> Tim apoteker berpengalaman siap membantu</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Partnership Kuat:</strong> Bermitra dengan 100+ brand farmasi terpercaya</li>
                <li><i class="fa-solid fa-envelope" style="color:#1E88E5;"></i> Email: <a href="mailto:medikpedia.mitramedika@gmail.com" style="color:#1E88E5;text-decoration:none;">medikpedia.mitramedika@gmail.com</a></li>
            </ul>
        </div>

        {{-- Layanan Kami --}}
        <div class="section-card">
            <h2><i class="fa-solid fa-cogs"></i> Layanan Kami</h2>
            <div class="vm-grid">
                <div class="vm-card vm-card-visi">
                    <h3><i class="fa-solid fa-truck"></i> Distributor Farmasi</h3>
                    <p>Sebagai distributor resmi, kami menyediakan layanan distribusi obat-obatan ke apotek, rumah sakit, klinik, dan institusi kesehatan lainnya dengan sistem supply chain yang terintegrasi dan terpercaya.</p>
                </div>
                <div class="vm-card vm-card-misi">
                    <h3><i class="fa-solid fa-store"></i> Apotik Online</h3>
                    <p>Platform apotik online modern dengan fitur konsultasi langsung, pencarian obat yang mudah, sistem pembayaran aman, dan pengiriman cepat langsung ke rumah Anda.</p>
                </div>
            </div>
            <ul class="check-list" style="margin-top: 1rem;">
                <li><i class="fa-solid fa-circle-check"></i> <strong>B2B Distribution:</strong> Layanan khusus untuk apotek dan institusi kesehatan</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>B2C Retail:</strong> Penjualan langsung ke konsumen melalui platform online</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Cold Chain Management:</strong> Penanganan khusus untuk obat yang memerlukan suhu terkontrol</li>
                <li><i class="fa-solid fa-circle-check"></i> <strong>Inventory Management:</strong> Sistem stok real-time untuk memastikan ketersediaan produk</li>
            </ul>
        </div>

        {{-- Nilai --}}
        <div class="section-card">
            <h2><i class="fa-solid fa-gem"></i> Nilai-Nilai Kami</h2>
            <div class="values-grid">
                <div class="value-item">
                    <i class="fa-solid fa-heart" style="color:#ef4444;"></i>
                    <h4>Kepercayaan</h4>
                    <p>Produk asli dan layanan yang jujur selalu menjadi prioritas kami.</p>
                </div>
                <div class="value-item">
                    <i class="fa-solid fa-shield-halved" style="color:#1E88E5;"></i>
                    <h4>Kualitas</h4>
                    <p>Seleksi ketat dan sertifikasi farmasi lengkap untuk setiap produk.</p>
                </div>
                <div class="value-item">
                    <i class="fa-solid fa-handshake" style="color:#7CB342;"></i>
                    <h4>Kemanusiaan</h4>
                    <p>Kesehatan adalah hak semua orang — kami hadir untuk semua kalangan.</p>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="cta-box">
            <h3>Ada pertanyaan? Hubungi kami sekarang</h3>
            <p>Tim kami siap membantu Anda melalui WhatsApp kapan saja</p>
            <a href="{{ route('contact') }}" class="cta-btn">
                <i class="fa-solid fa-headset"></i> Hubungi Kami
            </a>
        </div>

    </div>
</div>

@endsection
