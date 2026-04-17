@extends('layouts.frontend')

@section('title', 'Produk Grosir - Medikpedia')

@section('styles')
<style>
    .products-header {
        background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1E88E5 100%);
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
    }
    .products-header::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(124,179,66,0.18) 0%, transparent 70%);
        border-radius: 50%;
    }
    .products-header .header-deco-icon {
        position: absolute;
        color: rgba(255,255,255,0.08);
        pointer-events: none;
        animation: headerIconFloat 6s ease-in-out infinite;
    }
    .products-header .header-deco-icon-1 { bottom: 10px; right: 12%; font-size: 4rem; animation-delay: 0s; }
    .products-header .header-deco-icon-2 { top: 15px;   right: 28%; font-size: 3rem; animation-delay: 2s; }
    .products-header .header-deco-icon-3 { bottom: 20px; right: 40%; font-size: 2.5rem; animation-delay: 4s; }
    @keyframes headerIconFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.08; }
        50%       { transform: translateY(-12px) rotate(8deg); opacity: 0.14; }
    }
    .products-header h1 {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800; color: white;
        margin-bottom: 0.5rem; position: relative;
    }
    .products-header p { color: rgba(255,255,255,0.8); font-size: 1rem; position: relative; }
    .breadcrumb-custom { display: flex; gap: 0.5rem; align-items: center; margin-bottom: 1rem; position: relative; }
    .breadcrumb-custom a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; }
    .breadcrumb-custom a:hover { color: white; }
    .breadcrumb-custom span { color: rgba(255,255,255,0.5); font-size: 0.9rem; }
    .breadcrumb-custom .current { color: #a5d65a; font-size: 0.9rem; font-weight: 600; }

    .products-main { background: transparent; padding: 2.5rem 0 5rem; min-height: 60vh; }

    .filter-bar {
        background: white; border-radius: 16px; padding: 1.25rem 1.5rem;
        margin-bottom: 2rem; box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb; display: flex; gap: 0.75rem;
        flex-wrap: wrap; align-items: flex-end;
    }
    .filter-group { flex: 1; min-width: 160px; }
    .filter-label { display: block; font-weight: 600; font-size: 0.8rem; color: #374151; margin-bottom: 0.35rem; }
    .filter-input, .filter-select {
        width: 100%; padding: 0.6rem 0.9rem; border: 1.5px solid #e5e7eb;
        border-radius: 10px; font-size: 0.9rem; color: #374151;
        background: #f9fafb; transition: all 0.2s; outline: none;
    }
    .filter-input:focus, .filter-select:focus {
        border-color: #1E88E5; background: white;
        box-shadow: 0 0 0 3px rgba(30,136,229,0.1);
    }
    .btn-filter {
        padding: 0.6rem 1.4rem; background: linear-gradient(135deg, #1E88E5, #1565C0);
        color: white; border: none; border-radius: 10px; cursor: pointer;
        font-weight: 600; font-size: 0.9rem; transition: all 0.3s; white-space: nowrap;
    }
    .btn-filter:hover { background: linear-gradient(135deg, #1565C0, #0D47A1); transform: translateY(-2px); }
    .btn-reset {
        padding: 0.6rem 1rem; background: white; color: #6b7280;
        border: 1.5px solid #e5e7eb; border-radius: 10px; cursor: pointer;
        font-weight: 600; font-size: 0.9rem; text-decoration: none; white-space: nowrap; transition: all 0.2s;
    }
    .btn-reset:hover { border-color: #ef4444; color: #ef4444; }

    .result-info { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem; }
    .result-info p { color: #6b7280; font-size: 0.875rem; margin: 0; }

    .medicines-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.5rem; margin-bottom: 2.5rem;
    }
    .medicine-card {
        background: white; border-radius: 16px; overflow: hidden;
        border: 1px solid #e5e7eb; transition: all 0.3s;
        display: flex; flex-direction: column;
    }
    .medicine-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 35px rgba(30,136,229,0.12);
        border-color: #90caf9;
    }
    .medicine-image {
        width: 100%; height: 180px;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; overflow: hidden;
    }
    .medicine-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
    .medicine-card:hover .medicine-image img { transform: scale(1.05); }
    .medicine-body { padding: 1.1rem; flex: 1; display: flex; flex-direction: column; }
    .medicine-company {
        display: inline-block; background: #e3f2fd; color: #1565C0;
        padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.72rem;
        font-weight: 700; margin-bottom: 0.5rem; letter-spacing: 0.3px;
    }
    .medicine-name {
        font-size: 0.95rem; font-weight: 700; margin-bottom: 0.5rem; color: #1f2937;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden; line-height: 1.4; flex: 1;
    }
    .medicine-price { font-size: 1.15rem; font-weight: 800; color: #1E88E5; margin-bottom: 0.5rem; }
    .stock-badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.85rem; }
    .stock-available { background: #d1fae5; color: #065f46; }
    .stock-low       { background: #fef3c7; color: #92400e; }
    .stock-out       { background: #fee2e2; color: #7f1d1d; }
    .medicine-btn {
        display: block; width: 100%; padding: 0.65rem;
        background: linear-gradient(135deg, #1E88E5, #1565C0);
        color: white; border: none; border-radius: 10px; cursor: pointer;
        font-weight: 700; font-size: 0.875rem; text-align: center;
        text-decoration: none; transition: all 0.3s;
    }
    .medicine-btn:hover {
        background: linear-gradient(135deg, #1565C0, #0D47A1);
        transform: translateY(-2px); color: white;
        box-shadow: 0 4px 12px rgba(30,136,229,0.3);
    }
    .btn-cart {
        display: block; width: 100%; padding: 0.55rem;
        background: white; color: #1E88E5;
        border: 2px solid #1E88E5; border-radius: 10px; cursor: pointer;
        font-weight: 700; font-size: 0.82rem; text-align: center;
        text-decoration: none; transition: all 0.3s; margin-top: 0.5rem;
    }
    .btn-cart:hover { background: #e3f2fd; transform: translateY(-1px); }
    .btn-cart.added { background: #d1fae5; color: #065f46; border-color: #34d399; }

    /* ===== CART DRAWER ===== */
    .cart-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.45);
        z-index: 2000; opacity: 0; pointer-events: none; transition: opacity 0.3s;
    }
    .cart-overlay.open { opacity: 1; pointer-events: all; }
    .cart-drawer {
        position: fixed; top: 0; right: -420px; width: 420px; max-width: 100vw;
        height: 100vh; background: white; z-index: 2001;
        display: flex; flex-direction: column;
        box-shadow: -8px 0 40px rgba(0,0,0,0.15);
        transition: right 0.35s cubic-bezier(.4,0,.2,1);
    }
    .cart-drawer.open { right: 0; }
    .cart-header {
        background: linear-gradient(135deg, #0D47A1, #1E88E5);
        padding: 1.25rem 1.5rem; color: white;
        display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
    }
    .cart-header h2 { font-size: 1.1rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .cart-close { background: rgba(255,255,255,0.2); border: none; color: white; width: 34px; height: 34px; border-radius: 50%; cursor: pointer; font-size: 1rem; display: flex; align-items: center; justify-content: center; transition: background 0.2s; }
    .cart-close:hover { background: rgba(255,255,255,0.35); }
    .cart-items { flex: 1; overflow-y: auto; padding: 1rem 1.25rem; }
    .cart-empty { text-align: center; padding: 3rem 1rem; color: #9ca3af; }
    .cart-empty i { font-size: 3rem; display: block; margin-bottom: 0.75rem; }
    .cart-item {
        display: flex; gap: 0.75rem; align-items: flex-start;
        padding: 0.85rem 0; border-bottom: 1px solid #f3f4f6;
    }
    .cart-item-img {
        width: 52px; height: 52px; border-radius: 10px; flex-shrink: 0;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .cart-item-img img { width: 100%; height: 100%; object-fit: cover; }
    .cart-item-info { flex: 1; min-width: 0; }
    .cart-item-name { font-size: 0.85rem; font-weight: 700; color: #1f2937; margin-bottom: 0.2rem; line-height: 1.3; }
    .cart-item-price { font-size: 0.82rem; color: #1E88E5; font-weight: 700; }
    .cart-item-qty { display: flex; align-items: center; gap: 0.4rem; margin-top: 0.4rem; }
    .qty-btn { width: 26px; height: 26px; border-radius: 6px; border: 1.5px solid #e5e7eb; background: white; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; justify-content: center; transition: all 0.2s; font-weight: 700; color: #374151; }
    .qty-btn:hover { border-color: #1E88E5; color: #1E88E5; }
    .qty-num { font-size: 0.85rem; font-weight: 700; min-width: 20px; text-align: center; }
    .cart-item-remove { background: none; border: none; color: #d1d5db; cursor: pointer; font-size: 0.9rem; padding: 0.2rem; transition: color 0.2s; flex-shrink: 0; }
    .cart-item-remove:hover { color: #ef4444; }
    .cart-footer { padding: 1.25rem 1.5rem; border-top: 2px solid #f3f4f6; flex-shrink: 0; background: #fafbff; }
    .cart-total { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
    .cart-total span { font-size: 0.9rem; color: #6b7280; }
    .cart-total strong { font-size: 1.2rem; color: #1E88E5; font-weight: 800; }
    .btn-wa {
        display: flex; align-items: center; justify-content: center; gap: 0.6rem;
        width: 100%; padding: 0.85rem; background: #25D366; color: white;
        border: none; border-radius: 12px; cursor: pointer; font-weight: 700;
        font-size: 1rem; text-decoration: none; transition: all 0.3s;
    }
    .btn-wa:hover { background: #1ebe5d; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,211,102,0.4); color: white; }
    .btn-wa:disabled { background: #d1d5db; cursor: not-allowed; transform: none; box-shadow: none; }
    .btn-clear { display: block; width: 100%; padding: 0.5rem; background: none; border: none; color: #9ca3af; font-size: 0.8rem; cursor: pointer; margin-top: 0.5rem; transition: color 0.2s; }
    .btn-clear:hover { color: #ef4444; }

    /* Cart badge di navbar */
    .cart-nav-btn {
        position: relative; background: none; border: none; cursor: pointer;
        color: white; padding: 0.5rem 0.6rem; border-radius: 0.375rem;
        font-size: 1rem; display: flex; align-items: center; transition: background 0.2s;
    }
    .cart-nav-btn:hover { background: rgba(255,255,255,0.2); }
    .cart-badge {
        position: absolute; top: 2px; right: 2px;
        background: #ef4444; color: white; font-size: 0.6rem; font-weight: 800;
        width: 16px; height: 16px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        display: none;
    }

    .empty-state { text-align: center; padding: 5rem 2rem; background: white; border-radius: 16px; border: 1px solid #e5e7eb; }
    .empty-state h3 { font-size: 1.4rem; font-weight: 700; color: #1f2937; margin: 1rem 0 0.5rem; }
    .empty-state p  { color: #6b7280; }

    .pagination-wrap { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-top: 1rem; }
    .pagination-wrap .info { color: #6b7280; font-size: 0.875rem; }
    .pagination-btns { display: flex; gap: 0.35rem; align-items: center; }
    .page-btn {
        padding: 0.4rem 0.75rem; border-radius: 0.4rem; background: white;
        color: #374151; font-size: 0.875rem; text-decoration: none;
        border: 1px solid #e5e7eb; min-width: 36px; text-align: center; transition: all 0.2s;
    }
    .page-btn:hover  { background: #1E88E5; color: white; border-color: #1E88E5; }
    .page-btn.active { background: #1E88E5; color: white; border-color: #1E88E5; font-weight: 700; }
    .page-btn.disabled { background: #f3f4f6; color: #d1d5db; cursor: not-allowed; pointer-events: none; }

    @media (max-width: 768px) {
        .filter-bar { flex-direction: column; padding: 1rem; gap: 0.75rem; }
        .filter-group { width: 100%; min-width: unset; }
        .filter-bar > div:last-child { width: 100%; display: flex; gap: 0.5rem; }
        .btn-filter, .btn-reset { flex: 1; text-align: center; }
        .medicines-grid { grid-template-columns: repeat(2, 1fr); gap: 0.85rem; }
        .cart-drawer { width: 100vw; right: -100vw; }
        .cart-drawer.open { right: 0; }
    }

    @media (max-width: 480px) {
        .medicines-grid { grid-template-columns: repeat(2, 1fr); gap: 0.65rem; }
        .medicine-image { height: 120px; }
        .medicine-body { padding: 0.65rem; }
        .medicine-name { font-size: 0.82rem; }
        .medicine-price { font-size: 0.9rem; }
        .medicine-btn, .btn-cart { font-size: 0.78rem; padding: 0.5rem; }
    }
</style>
@endsection

@section('content')

<div class="products-header">
    <div class="container">
        <div class="breadcrumb-custom">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            <span>/</span>
            <span class="current">Produk</span>
        </div>
        <h1><i class="fa-solid fa-boxes-stacked"></i> Katalog Produk Grosir</h1>
        <p>{{ $total }} produk tersedia dari berbagai perusahaan farmasi terpercaya</p>
    </div>
    <i class="fa-solid fa-pills header-deco-icon header-deco-icon-1"></i>
    <i class="fa-solid fa-prescription-bottle-medical header-deco-icon header-deco-icon-2"></i>
    <i class="fa-solid fa-stethoscope header-deco-icon header-deco-icon-3"></i>
</div>

<div class="products-main">
    <div class="container">

        <form method="GET" action="{{ route('products.grosir') }}" class="filter-bar">
            <div class="filter-group" style="flex: 2; min-width: 200px;">
                <label class="filter-label"><i class="fa-solid fa-magnifying-glass"></i> Cari Produk</label>
                <input type="text" name="search" class="filter-input"
                       placeholder="Nama obat atau deskripsi..."
                       value="{{ $search }}">
            </div>
            <div class="filter-group">
                <label class="filter-label"><i class="fa-solid fa-building"></i> Perusahaan</label>
                <select name="perusahaan" class="filter-select">
                    <option value="">Semua Perusahaan</option>
                    @foreach($perusahaans as $p)
                        <option value="{{ $p }}" @selected($perusahaan === $p)>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label"><i class="fa-solid fa-arrow-up-wide-short"></i> Urutkan</label>
                <select name="sort" class="filter-select">
                    <option value="terbaru"    @selected($sort === 'terbaru')>Terbaru</option>
                    <option value="harga_asc"  @selected($sort === 'harga_asc')>Harga Terendah</option>
                    <option value="harga_desc" @selected($sort === 'harga_desc')>Harga Tertinggi</option>
                    <option value="nama"       @selected($sort === 'nama')>Nama A–Z</option>
                </select>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: flex-end;">
                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>
                @if($search || $perusahaan || $sort !== 'terbaru')
                    <a href="{{ route('products.grosir') }}" class="btn-reset">✕ Reset</a>
                @endif
            </div>
        </form>

        <div class="result-info">
            <p>
                Menampilkan <strong>{{ $medicines->firstItem() ?? 0 }}–{{ $medicines->lastItem() ?? 0 }}</strong>
                dari <strong>{{ $medicines->total() }}</strong> produk
                @if($search) · "<strong>{{ $search }}</strong>" @endif
                @if($perusahaan) · <strong>{{ $perusahaan }}</strong> @endif
            </p>
        </div>

        @if($medicines->count() > 0)
            <div class="medicines-grid">
                @foreach($medicines as $medicine)
                    <div class="medicine-card">
                        <div class="medicine-image">
                            @if($medicine->gambar)
                                <img src="{{ asset('storage/' . $medicine->gambar) }}" alt="{{ $medicine->nama_obat }}">
                            @else
                                <i class="fa-solid fa-pills" style="color:#90caf9;font-size:3rem;"></i>
                            @endif
                        </div>
                        <div class="medicine-body">
                            <span class="medicine-company">{{ $medicine->kategori }}</span>
                            <h3 class="medicine-name">{{ $medicine->nama_obat }}</h3>
                            <div class="medicine-price">{{ $medicine->getFormattedPrice() }}</div>
                            @if($medicine->stok > 10)
                                <span class="stock-badge stock-available"><i class="fa-solid fa-circle-check"></i> {{ $medicine->stok }} tersedia</span>
                            @elseif($medicine->stok > 0)
                                <span class="stock-badge stock-low"><i class="fa-solid fa-triangle-exclamation"></i> {{ $medicine->stok }} tersisa</span>
                            @else
                                <span class="stock-badge stock-out"><i class="fa-solid fa-circle-xmark"></i> Habis</span>
                            @endif
                            <a href="{{ route('medicines.show', $medicine->id) }}" class="medicine-btn">
                                Lihat Detail <i class="fa-solid fa-arrow-right"></i>
                            </a>
                            @if($medicine->stok > 0)
                            <button class="btn-cart" onclick="addToCart({{ $medicine->id }}, '{{ addslashes($medicine->nama_obat) }}', {{ $medicine->harga }}, '{{ $medicine->gambar ? asset('storage/'.$medicine->gambar) : '' }}', this)">
                                <i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang
                            </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrap">
                <p class="info">Halaman {{ $medicines->currentPage() }} dari {{ $medicines->lastPage() }}</p>
                <div class="pagination-btns">
                    @if($medicines->onFirstPage())
                        <span class="page-btn disabled">‹</span>
                    @else
                        <a href="{{ $medicines->previousPageUrl() }}" class="page-btn">‹</a>
                    @endif

                    @foreach($medicines->getUrlRange(1, $medicines->lastPage()) as $page => $url)
                        @if($page == $medicines->currentPage())
                            <span class="page-btn active">{{ $page }}</span>
                        @elseif($page == 1 || $page == $medicines->lastPage() || abs($page - $medicines->currentPage()) <= 2)
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @elseif(abs($page - $medicines->currentPage()) == 3)
                            <span class="page-btn disabled">…</span>
                        @endif
                    @endforeach

                    @if($medicines->hasMorePages())
                        <a href="{{ $medicines->nextPageUrl() }}" class="page-btn">›</a>
                    @else
                        <span class="page-btn disabled">›</span>
                    @endif
                </div>
            </div>

        @else
            <div class="empty-state">
                <i class="fa-solid fa-box-open" style="font-size:3.5rem;color:#d1d5db;"></i>
                <h3>Produk tidak ditemukan</h3>
                <p>
                    @if($search || $perusahaan)
                        Coba ubah kata kunci atau filter pencarian.
                    @else
                        Belum ada produk tersedia.
                    @endif
                </p>
                @if($search || $perusahaan)
                    <a href="{{ route('products.grosir') }}" class="btn-reset" style="display:inline-block;margin-top:1rem;">✕ Hapus Filter</a>
                @endif
            </div>
        @endif

    </div>
</div>

@endsection

@section('scripts')
<!-- Cart Overlay & Drawer -->
<div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>
<div class="cart-drawer" id="cartDrawer">
    <div class="cart-header">
        <h2><i class="fa-solid fa-cart-shopping"></i> Keranjang Belanja</h2>
        <button class="cart-close" onclick="closeCart()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="cart-items" id="cartItems">
        <div class="cart-empty" id="cartEmpty">
            <i class="fa-solid fa-cart-shopping"></i>
            <p>Keranjang masih kosong</p>
        </div>
    </div>
    <div class="cart-footer" id="cartFooter" style="display:none;">
        <div class="cart-total">
            <span>Total Pesanan</span>
            <strong id="cartTotal">Rp 0</strong>
        </div>
        <button class="btn-wa" onclick="openOrderForm()">
            <i class="fa-brands fa-whatsapp" style="font-size:1.3rem;"></i>
            Pesan via WhatsApp
        </button>
        <button class="btn-clear" onclick="clearCart()">Kosongkan keranjang</button>
    </div>
</div>

<!-- Modal Form Pemesanan Grosir -->
<div id="orderOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:3000;" onclick="closeOrderForm()"></div>
<div id="orderModal" style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width:92%;max-width:520px;max-height:90vh;overflow-y:auto;background:white;border-radius:20px;z-index:3001;box-shadow:0 25px 60px rgba(0,0,0,0.25);">
    <div style="background:linear-gradient(135deg,#1565C0,#1E88E5);padding:1.25rem 1.5rem;border-radius:20px 20px 0 0;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:1;">
        <div>
            <h3 style="color:white;margin:0;font-size:1rem;font-weight:700;"><i class="fa-brands fa-whatsapp"></i> Form Pemesanan</h3>
            <p style="color:rgba(255,255,255,0.8);margin:0;font-size:0.78rem;">Produk Grosir</p>
        </div>
        <button onclick="closeOrderForm()" style="background:rgba(255,255,255,0.2);border:none;color:white;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1rem;">✕</button>
    </div>

    <!-- Ringkasan pesanan -->
    <div style="padding:1rem 1.5rem;background:#f8faff;border-bottom:1px solid #e5e7eb;">
        <p style="font-size:0.78rem;font-weight:700;color:#6b7280;margin:0 0 0.5rem;text-transform:uppercase;">Ringkasan Pesanan</p>
        <div id="orderSummary" style="font-size:0.85rem;color:#374151;"></div>
        <div style="display:flex;justify-content:space-between;margin-top:0.5rem;padding-top:0.5rem;border-top:1px solid #e5e7eb;">
            <span style="font-weight:700;color:#374151;">Total</span>
            <span id="orderTotalDisplay" style="font-weight:800;color:#1E88E5;font-size:1rem;"></span>
        </div>
    </div>

    <!-- Form -->
    <div style="padding:1.25rem 1.5rem;">
        <p style="font-size:0.78rem;font-weight:700;color:#6b7280;margin:0 0 1rem;text-transform:uppercase;">Formulir Pemesanan</p>

        <div style="margin-bottom:0.85rem;">
            <label style="display:block;font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:0.35rem;">Nama Pemesan <span style="color:#ef4444;">*</span></label>
            <input id="g_nama" type="text" placeholder="Nama lengkap" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.9rem;outline:none;" onfocus="this.style.borderColor='#1E88E5'" onblur="this.style.borderColor='#e5e7eb'">
        </div>
        <div style="margin-bottom:0.85rem;">
            <label style="display:block;font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:0.35rem;">Nama Outlet <span style="color:#ef4444;">*</span></label>
            <input id="g_outlet" type="text" placeholder="Nama apotek / klinik / toko" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.9rem;outline:none;" onfocus="this.style.borderColor='#1E88E5'" onblur="this.style.borderColor='#e5e7eb'">
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:0.85rem;">
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:0.35rem;">No. SIA <span style="color:#ef4444;">*</span></label>
                <input id="g_sia" type="text" placeholder="No. SIA" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.9rem;outline:none;" onfocus="this.style.borderColor='#1E88E5'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:0.35rem;">No. SIPA <span style="color:#ef4444;">*</span></label>
                <input id="g_sipa" type="text" placeholder="No. SIPA" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.9rem;outline:none;" onfocus="this.style.borderColor='#1E88E5'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
        </div>
        <div style="margin-bottom:0.85rem;">
            <label style="display:block;font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:0.35rem;">Alamat <span style="color:#ef4444;">*</span></label>
            <textarea id="g_alamat" rows="2" placeholder="Jl. nama jalan, no. rumah, RT/RW..." style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.9rem;outline:none;resize:vertical;" onfocus="this.style.borderColor='#1E88E5'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:0.85rem;">
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:0.35rem;">Kelurahan</label>
                <input id="g_kelurahan" type="text" placeholder="Kelurahan" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.9rem;outline:none;" onfocus="this.style.borderColor='#1E88E5'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:0.35rem;">Kecamatan</label>
                <input id="g_kecamatan" type="text" placeholder="Kecamatan" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.9rem;outline:none;" onfocus="this.style.borderColor='#1E88E5'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:0.85rem;">
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:0.35rem;">Kab / Kota</label>
                <input id="g_kota" type="text" placeholder="Kab / Kota" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.9rem;outline:none;" onfocus="this.style.borderColor='#1E88E5'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
            <div>
                <label style="display:block;font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:0.35rem;">Kodepos</label>
                <input id="g_kodepos" type="text" placeholder="Kodepos" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.9rem;outline:none;" onfocus="this.style.borderColor='#1E88E5'" onblur="this.style.borderColor='#e5e7eb'">
            </div>
        </div>
        <div style="margin-bottom:0.85rem;">
            <label style="display:block;font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:0.35rem;">No. HP / WA <span style="color:#ef4444;">*</span></label>
            <input id="g_hp" type="tel" placeholder="08xxxxxxxxxx" style="width:100%;padding:0.6rem 0.85rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.9rem;outline:none;" onfocus="this.style.borderColor='#1E88E5'" onblur="this.style.borderColor='#e5e7eb'">
        </div>

        <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:10px;padding:0.75rem 1rem;margin-bottom:1rem;font-size:0.8rem;color:#92400e;">
            <i class="fa-solid fa-circle-info"></i> <strong>Mohon disertakan foto lampiran:</strong> KTP, NPWP, SIA, SIPA untuk proses registrasi pelanggan.
        </div>

        <div id="orderError" style="display:none;background:#fee2e2;color:#7f1d1d;padding:0.6rem 0.85rem;border-radius:8px;font-size:0.82rem;margin-bottom:0.85rem;"></div>

        <button onclick="submitGrosirOrder()" style="width:100%;padding:0.85rem;background:linear-gradient(135deg,#25D366,#1ebe5d);color:white;border:none;border-radius:12px;font-size:1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;transition:all 0.2s;">
            <i class="fa-brands fa-whatsapp" style="font-size:1.2rem;"></i> Kirim Pesanan via WhatsApp
        </button>
    </div>
</div>

<script>
const WA_NUMBER = '6285890007359';
let cart = JSON.parse(localStorage.getItem('medikpedia_cart_grosir') || '[]');

function saveCart() {
    localStorage.setItem('medikpedia_cart_grosir', JSON.stringify(cart));
    updateBadge();
}

function updateBadge() {
    const total = cart.reduce((s, i) => s + i.qty, 0);
    document.querySelectorAll('.cart-badge').forEach(b => {
        b.textContent = total;
        b.style.display = total > 0 ? 'flex' : 'none';
    });
    const navBtn = document.getElementById('cartNavBtn');
    if (navBtn) navBtn.style.display = total > 0 ? 'flex' : 'none';
}

function addToCart(id, name, price, img, btn) {
    const existing = cart.find(i => i.id === id);
    if (existing) { existing.qty++; }
    else { cart.push({ id, name, price, img, qty: 1 }); }
    saveCart();
    renderCart();
    btn.classList.add('added');
    btn.innerHTML = '<i class="fa-solid fa-check"></i> Ditambahkan!';
    setTimeout(() => {
        btn.classList.remove('added');
        btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Tambah ke Keranjang';
    }, 1500);
    openCart();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    saveCart(); renderCart();
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) removeFromCart(id);
    else { saveCart(); renderCart(); }
}

function setQtyInput(id, val) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    const num = parseInt(val);
    if (isNaN(num) || num < 1) return;
    item.qty = num;
    saveCart(); renderCart();
}

function clearCart() {
    cart = []; saveCart(); renderCart();
}

function formatRp(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const empty     = document.getElementById('cartEmpty');
    const footer    = document.getElementById('cartFooter');
    const totalEl   = document.getElementById('cartTotal');

    if (cart.length === 0) {
        footer.style.display = 'none';
        container.innerHTML = `<div class="cart-empty"><i class="fa-solid fa-cart-shopping"></i><p>Keranjang masih kosong</p></div>`;
        return;
    }

    footer.style.display = '';
    let html = '', total = 0;
    cart.forEach(item => {
        total += item.price * item.qty;
        const imgHtml = item.img
            ? `<img src="${item.img}" alt="${item.name}">`
            : `<i class="fa-solid fa-pills" style="color:#90caf9;font-size:1.4rem;"></i>`;
        html += `
        <div class="cart-item">
            <div class="cart-item-img">${imgHtml}</div>
            <div class="cart-item-info">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">${formatRp(item.price)}</div>
                <div class="cart-item-qty">
                    <button class="qty-btn" onclick="changeQty(${item.id}, -1)">−</button>
                    <input class="qty-num" type="number" min="1" value="${item.qty}"
                        style="width:44px;text-align:center;border:1.5px solid #e5e7eb;border-radius:6px;font-size:0.85rem;font-weight:700;padding:2px 4px;"
                        onchange="setQtyInput(${item.id}, this.value)"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    <button class="qty-btn" onclick="changeQty(${item.id}, 1)">+</button>
                </div>
            </div>
            <button class="cart-item-remove" onclick="removeFromCart(${item.id})" title="Hapus"><i class="fa-solid fa-trash"></i></button>
        </div>`;
    });
    container.innerHTML = html;
    totalEl.textContent = formatRp(total);
}

function openCart() {
    document.getElementById('cartDrawer').classList.add('open');
    document.getElementById('cartOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
    renderCart();
}

function closeCart() {
    document.getElementById('cartDrawer').classList.remove('open');
    document.getElementById('cartOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function openOrderForm() {
    if (cart.length === 0) return;
    let html = '', total = 0;
    cart.forEach((item, i) => {
        const sub = item.price * item.qty;
        total += sub;
        html += `<div style="display:flex;justify-content:space-between;padding:0.25rem 0;border-bottom:1px solid #f3f4f6;">
            <span style="flex:1;">${i+1}. ${item.name} <span style="color:#9ca3af;">×${item.qty}</span></span>
            <span style="font-weight:700;color:#1E88E5;white-space:nowrap;margin-left:0.5rem;">${formatRp(sub)}</span>
        </div>`;
    });
    document.getElementById('orderSummary').innerHTML = html;
    document.getElementById('orderTotalDisplay').textContent = formatRp(total);
    document.getElementById('orderOverlay').style.display = 'block';
    document.getElementById('orderModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeOrderForm() {
    document.getElementById('orderOverlay').style.display = 'none';
    document.getElementById('orderModal').style.display = 'none';
    document.body.style.overflow = '';
}

function submitGrosirOrder() {
    const nama    = document.getElementById('g_nama').value.trim();
    const outlet  = document.getElementById('g_outlet').value.trim();
    const sia     = document.getElementById('g_sia').value.trim();
    const sipa    = document.getElementById('g_sipa').value.trim();
    const alamat  = document.getElementById('g_alamat').value.trim();
    const hp      = document.getElementById('g_hp').value.trim();
    const errEl   = document.getElementById('orderError');

    if (!nama || !outlet || !sia || !sipa || !alamat || !hp) {
        errEl.textContent = 'Nama, Outlet, SIA, SIPA, Alamat, dan No. HP wajib diisi.';
        errEl.style.display = 'block';
        return;
    }
    errEl.style.display = 'none';

    const kelurahan = document.getElementById('g_kelurahan').value.trim();
    const kecamatan = document.getElementById('g_kecamatan').value.trim();
    const kota      = document.getElementById('g_kota').value.trim();
    const kodepos   = document.getElementById('g_kodepos').value.trim();

    let msg = '🛒 *Halo Medikpedia, saya ingin memesan:*\n\n';
    let total = 0;
    cart.forEach((item, i) => {
        const sub = item.price * item.qty;
        total += sub;
        msg += `${i+1}. *${item.name}*\n   Qty: ${item.qty} × ${formatRp(item.price)} = ${formatRp(sub)}\n\n`;
    });
    msg += `━━━━━━━━━━━━━━━\n💰 *Total: ${formatRp(total)}*\n\n`;
    msg += `📋 *Formulir Pemesanan:*\n`;
    msg += `- Nama Pemesan : ${nama}\n`;
    msg += `- Nama Outlet  : ${outlet}\n`;
    msg += `- No. SIA      : ${sia}\n`;
    msg += `- No. SIPA     : ${sipa}\n`;
    msg += `- Alamat       : ${alamat}\n`;
    if (kelurahan) msg += `- Kelurahan    : ${kelurahan}\n`;
    if (kecamatan) msg += `- Kecamatan    : ${kecamatan}\n`;
    if (kota)      msg += `- Kab / Kota   : ${kota}\n`;
    if (kodepos)   msg += `- Kodepos      : ${kodepos}\n`;
    msg += `- No HP / WA   : ${hp}\n\n`;
    msg += `📎 *Mohon disertakan Foto Lampiran:*\nKTP, NPWP, SIA, SIPA\nuntuk proses registrasi pelanggan.\n\n`;
    msg += `Terima kasih, Salam, Medikpedia 🙏`;

    window.open(`https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(msg)}`, '_blank');
    closeOrderForm();
}

function orderViaWhatsApp() {
    openOrderForm();
}

document.addEventListener('DOMContentLoaded', () => {
    updateBadge();
    if (window.location.hash === '#keranjang') openCart();

    // Tombol cart navbar — buka drawer langsung di halaman ini
    const navBtn = document.getElementById('cartNavBtn');
    if (navBtn) {
        navBtn.onclick = function() { openCart(); };
    }
});
</script>
@endsection
