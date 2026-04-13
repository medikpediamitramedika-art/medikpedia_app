@extends('layouts.frontend')

@section('title', $medicine->nama_obat . ' - Medikpedia')

@section('styles')
<style>
    /* Detail Section */
    .detail-container {
        max-width: 1000px;
        margin: 3rem auto;
        padding: 0 1rem;
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        padding: 2rem;
    }

    .detail-image {
        aspect-ratio: 1;
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 6rem;
        overflow: hidden;
        position: sticky;
        top: 100px;
    }

    .detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .detail-info h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: var(--dark);
    }

    .detail-category {
        display: inline-block;
        background: #d1fae5;
        color: #065f46;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .stars {
        margin-bottom: 1rem;
    }

    .stars span {
        color: #fbbf24;
        font-size: 1.25rem;
    }

    .price-section {
        margin: 2rem 0;
        padding: 2rem;
        background: #f0fdf4;
        border-radius: 0.75rem;
        border-left: 4px solid #10b981;
    }

    .price {
        font-size: 2.5rem;
        font-weight: 700;
        color: #10b981;
        margin-bottom: 1rem;
    }

    .price-label {
        font-size: 0.875rem;
        color: #6b7280;
        text-transform: uppercase;
        font-weight: 600;
    }

    .stock-info {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stock-item {
        flex: 1;
        padding: 1rem;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        text-align: center;
    }

    .stock-item-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .stock-item-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
    }

    .description-section {
        margin: 2rem 0;
    }

    .description-section h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: var(--dark);
    }

    .description-section p {
        line-height: 1.6;
        color: #4b5563;
        margin-bottom: 1rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin: 2rem 0;
    }

    .btn-large {
        flex: 1;
        padding: 1rem;
        border: none;
        border-radius: 0.5rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-buy {
        background: #10b981;
        color: white;
    }

    .btn-buy:hover {
        background: #059669;
        transform: translateY(-2px);
    }

    .btn-buy:disabled {
        background: #d1d5db;
        cursor: not-allowed;
    }

    .btn-back {
        background: white;
        color: #10b981;
        border: 2px solid #10b981;
    }

    .btn-back:hover {
        background: #f0fdf4;
    }

    /* Related Products */
    .related-section {
        margin-top: 3rem;
        padding-top: 3rem;
        border-top: 2px solid #e5e7eb;
    }

    .related-section h2 {
        font-size: 1.5rem;
        margin-bottom: 2rem;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .related-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s;
    }

    .related-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-4px);
    }

    .related-image {
        width: 100%;
        height: 150px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-body {
        padding: 1rem;
    }

    .related-name {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--dark);
    }

    .related-price {
        font-size: 1.125rem;
        font-weight: 700;
        color: #10b981;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 1rem;
        }

        .detail-image {
            position: static;
        }

        .detail-info h1 {
            font-size: 1.5rem;
        }

        .price {
            font-size: 2rem;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
<div class="detail-container">
    <div class="detail-grid">
        <!-- Image -->
        <div class="detail-image">
            @if($medicine->gambar)
                <img src="{{ asset('storage/' . $medicine->gambar) }}" alt="{{ $medicine->nama_obat }}">
            @else
                💊
            @endif
        </div>

        <!-- Info -->
        <div class="detail-info">
            <span class="detail-category">{{ $medicine->kategori }}</span>
            <h1>{{ $medicine->nama_obat }}</h1>

            <!-- Price Section -->
            <div class="price-section">
                <span class="price-label">Harga</span>
                <div class="price">{{ $medicine->getFormattedPrice() }}</div>
            </div>

            <!-- Stock Info -->
            <div class="stock-info">
                <div class="stock-item">
                    <div class="stock-item-label">Stok Tersedia</div>
                    <div class="stock-item-value">{{ $medicine->stok }}</div>
                </div>
                <div class="stock-item">
                    <div class="stock-item-label">Status</div>
                    <div class="stock-item-value">
                        @if($medicine->isAvailable())
                            <span style="color: #10b981;">✓ Tersedia</span>
                        @else
                            <span style="color: #ef4444;">✕ Habis</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="description-section">
                <h3>Deskripsi Produk</h3>
                <p>{{ $medicine->deskripsi }}</p>
            </div>

            <!-- Form Pemesanan -->
            <div style="background: #f9fafb; border-radius: 0.75rem; padding: 1.5rem; margin: 1.5rem 0; border: 1px solid #e5e7eb;">
                <h3 style="font-size: 1rem; font-weight: 700; color: #1f2937; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-brands fa-whatsapp" style="color: #25D366;"></i> Form Pemesanan
                </h3>

                <!-- Jumlah -->
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.4rem;">
                        Jumlah Pembelian
                    </label>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <button type="button" onclick="changeQty(-1)"
                            style="width:36px;height:36px;border:1px solid #d1d5db;border-radius:0.5rem;background:white;font-size:1.1rem;cursor:pointer;font-weight:700;color:#374151;transition:all 0.2s;"
                            onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='white'">−</button>
                        <input type="number" id="qtyInput" value="1" min="1" max="{{ $medicine->stok }}"
                            style="width:70px;text-align:center;padding:0.4rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:1rem;font-weight:700;"
                            oninput="updateTotal()">
                        <button type="button" onclick="changeQty(1)"
                            style="width:36px;height:36px;border:1px solid #d1d5db;border-radius:0.5rem;background:white;font-size:1.1rem;cursor:pointer;font-weight:700;color:#374151;transition:all 0.2s;"
                            onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='white'">+</button>
                        <span style="font-size:0.8rem;color:#6b7280;">Stok: {{ $medicine->stok }}</span>
                    </div>
                </div>

                <!-- Total Harga -->
                <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; background: #e3f2fd; border-radius: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.875rem; color: #1565C0; font-weight: 600;">Total Harga</span>
                    <span id="totalHarga" style="font-size: 1.1rem; font-weight: 800; color: #1565C0;">{{ $medicine->getFormattedPrice() }}</span>
                </div>

                <!-- Alamat Pengiriman -->
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.4rem;">
                        Alamat Pengiriman <span style="color:#ef4444;">*</span>
                    </label>
                    <textarea id="alamatInput" rows="3" placeholder="Contoh: Jl. Merdeka No. 10, RT 02/RW 03, Kel. Menteng, Kec. Menteng, Jakarta Pusat 10310"
                        style="width:100%;padding:0.65rem 0.85rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:0.9rem;resize:vertical;font-family:inherit;outline:none;transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='#25D366'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                    <p id="alamatError" style="color:#ef4444;font-size:0.8rem;margin-top:0.25rem;display:none;">
                        <i class="fa-solid fa-circle-exclamation"></i> Alamat pengiriman wajib diisi.
                    </p>
                </div>

                <!-- Tombol Pesan -->
                <button onclick="pesanWA()"
                    style="width:100%;padding:0.9rem;background:#25D366;color:white;border:none;border-radius:0.5rem;font-size:1rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.6rem;transition:all 0.3s;"
                    onmouseover="this.style.background='#1ebe5d';this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.background='#25D366';this.style.transform='translateY(0)'">
                    <i class="fa-brands fa-whatsapp" style="font-size:1.2rem;"></i> Pesan via WhatsApp
                </button>
            </div>

            <a href="{{ route('home') }}" style="display:flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.7rem;border:1.5px solid #e5e7eb;border-radius:0.5rem;color:#6b7280;text-decoration:none;font-weight:600;font-size:0.9rem;transition:all 0.2s;"
               onmouseover="this.style.borderColor='#1E88E5';this.style.color='#1E88E5'" onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
            </a>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedMedicines->count() > 0)
        <div class="related-section">
            <h2>Produk Serupa dari Kategori {{ $medicine->kategori }}</h2>
            <div class="related-grid">
                @foreach($relatedMedicines as $related)
                    <a href="{{ route('medicines.show', $related->id) }}" class="related-card">
                        <div class="related-image">
                            @if($related->gambar)
                                <img src="{{ asset('storage/' . $related->gambar) }}" alt="{{ $related->nama_obat }}">
                            @else
                                💊
                            @endif
                        </div>
                        <div class="related-body">
                            <div class="related-name">{{ $related->nama_obat }}</div>
                            <div class="related-price">{{ $related->getFormattedPrice() }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    const hargaSatuan = {{ $medicine->harga }};
    const stokMax     = {{ $medicine->stok }};
    const namaObat    = @json($medicine->nama_obat);
    const kategori    = @json($medicine->kategori);

    function formatRupiah(angka) {
        return 'Rp ' + Math.round(angka).toLocaleString('id-ID');
    }

    function changeQty(delta) {
        const input = document.getElementById('qtyInput');
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > stokMax) val = stokMax;
        input.value = val;
        updateTotal();
    }

    function updateTotal() {
        const qty = Math.max(1, Math.min(parseInt(document.getElementById('qtyInput').value) || 1, stokMax));
        document.getElementById('qtyInput').value = qty;
        document.getElementById('totalHarga').textContent = formatRupiah(hargaSatuan * qty);
    }

    function pesanWA() {
        const qty    = parseInt(document.getElementById('qtyInput').value) || 1;
        const alamat = document.getElementById('alamatInput').value.trim();
        const errEl  = document.getElementById('alamatError');

        if (!alamat) {
            errEl.style.display = 'block';
            document.getElementById('alamatInput').focus();
            return;
        }
        errEl.style.display = 'none';

        const total          = formatRupiah(hargaSatuan * qty);
        const hargaSatuanFmt = formatRupiah(hargaSatuan);

        const pesan =
`Halo Medikpedia, saya ingin memesan:

🛒 *Detail Pesanan*
• Produk     : ${namaObat}
• Perusahaan : ${kategori}
• Harga      : ${hargaSatuanFmt} / pcs
• Jumlah     : ${qty} pcs
• Total      : ${total}

📍 *Alamat Pengiriman*
${alamat}

Mohon konfirmasi ketersediaan stok dan info pengiriman. Terima kasih!`;

        window.open('https://wa.me/6285890007359?text=' + encodeURIComponent(pesan), '_blank');
    }
</script>
@endsection
