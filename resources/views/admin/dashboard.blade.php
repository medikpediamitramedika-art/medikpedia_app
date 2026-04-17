@extends('layouts.admin')

@section('title', 'Dashboard - Admin Medikpedia')
@section('page-title', '📊 Dashboard')

@section('content')

<!-- Stats Grid Realtime -->
<div class="stats-grid" style="margin-top: 1rem;">
    <div class="stat-card">
        <div class="stat-label">Produk Retail</div>
        <div class="stat-value" id="stat-retail">{{ $totalMedicines }}</div>
        <div style="font-size:0.75rem;color:#9ca3af;margin-top:0.25rem;">
            <span id="stat-retail-new" style="color:#10b981;font-weight:700;display:none;"></span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Produk Grosir</div>
        <div class="stat-value" id="stat-grosir" style="color:#7C3AED;">{{ $totalGrosir }}</div>
        <div style="font-size:0.75rem;color:#9ca3af;margin-top:0.25rem;">
            <span id="stat-grosir-new" style="color:#10b981;font-weight:700;display:none;"></span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Semua Produk</div>
        <div class="stat-value" id="stat-total" style="color:#1E88E5;">{{ $totalMedicines + $totalGrosir }}</div>
    </div>
    <div class="stat-card" style="border-top-color: #f59e0b;">
        <div class="stat-label">Stok Rendah (Retail)</div>
        <div class="stat-value" id="stat-lowstok" style="color: #f59e0b;">{{ $lowStokMedicines }}</div>
    </div>
</div>

<!-- Realtime indicator -->
<div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1rem;font-size:0.8rem;color:#6b7280;">
    <span id="realtime-dot" style="width:8px;height:8px;border-radius:50%;background:#10b981;display:inline-block;animation:pulse 2s infinite;"></span>
    <span id="realtime-status">Memuat data realtime...</span>
    <span id="realtime-time" style="margin-left:auto;"></span>
</div>
<style>
@keyframes pulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:0.5; transform:scale(1.3); }
}
</style>

<!-- Produk Obat Terbaru (Retail + Grosir) -->
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <div class="card-title" style="margin:0;">🆕 Produk Obat Terbaru</div>
    </div>

    <!-- Tab buttons -->
    <div style="display:flex;gap:0;border-bottom:2px solid #e5e7eb;margin-bottom:1rem;">
        <button id="tab-retail" onclick="switchTab('retail')"
            style="padding:0.5rem 1.25rem;border:none;background:none;font-weight:700;font-size:0.875rem;cursor:pointer;border-bottom:3px solid #1E88E5;color:#1E88E5;margin-bottom:-2px;">
            💊 Retail <span id="badge-retail" style="background:#e3f2fd;color:#1565C0;border-radius:20px;padding:0.1rem 0.5rem;font-size:0.75rem;margin-left:0.3rem;">{{ $totalMedicines }}</span>
        </button>
        <button id="tab-grosir" onclick="switchTab('grosir')"
            style="padding:0.5rem 1.25rem;border:none;background:none;font-weight:700;font-size:0.875rem;cursor:pointer;border-bottom:3px solid transparent;color:#6b7280;margin-bottom:-2px;">
            📦 Grosir <span id="badge-grosir" style="background:#f3e8ff;color:#7C3AED;border-radius:20px;padding:0.1rem 0.5rem;font-size:0.75rem;margin-left:0.3rem;">{{ $totalGrosir }}</span>
        </button>
    </div>

    <!-- Tab Retail -->
    <div id="panel-retail">
        <div style="display:flex;justify-content:flex-end;margin-bottom:0.5rem;">
            <a href="{{ route('admin.medicines.index') }}" style="font-size:0.8rem;color:#1E88E5;text-decoration:none;">Lihat semua →</a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Perusahaan</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="retail-tbody">
                    @forelse($medicines as $medicine)
                        <tr>
                            <td><strong>{{ $medicine->nama_obat }}</strong></td>
                            <td>{{ $medicine->kategori }}</td>
                            <td>{{ $medicine->getFormattedPrice() }}</td>
                            <td>
                                @if($medicine->stok > 10)
                                    <span style="color:#10b981;"><strong>{{ $medicine->stok }}</strong></span>
                                @elseif($medicine->stok > 0)
                                    <span style="color:#f59e0b;"><strong>{{ $medicine->stok }}</strong></span>
                                @else
                                    <span style="color:#ef4444;"><strong>Habis</strong></span>
                                @endif
                            </td>
                            <td style="font-size:0.82rem;color:#9ca3af;">{{ $medicine->created_at->format('d M Y H:i') }}</td>
                            <td><a href="{{ route('admin.medicines.edit', $medicine->id) }}" class="btn btn-secondary btn-sm">Edit</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;color:#6b7280;padding:2rem;">Belum ada produk retail.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Grosir -->
    <div id="panel-grosir" style="display:none;">
        <div style="display:flex;justify-content:flex-end;margin-bottom:0.5rem;">
            <a href="{{ route('admin.grosir.index') }}" style="font-size:0.8rem;color:#7C3AED;text-decoration:none;">Lihat semua →</a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Obat</th>
                        <th>Perusahaan</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Ditambahkan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="grosir-tbody">
                    @forelse($latestGrosir as $medicine)
                        <tr>
                            <td><strong>{{ $medicine->nama_obat }}</strong></td>
                            <td>{{ $medicine->kategori }}</td>
                            <td>{{ $medicine->getFormattedPrice() }}</td>
                            <td>
                                @if($medicine->stok > 10)
                                    <span style="color:#10b981;"><strong>{{ $medicine->stok }}</strong></span>
                                @elseif($medicine->stok > 0)
                                    <span style="color:#f59e0b;"><strong>{{ $medicine->stok }}</strong></span>
                                @else
                                    <span style="color:#ef4444;"><strong>Habis</strong></span>
                                @endif
                            </td>
                            <td style="font-size:0.82rem;color:#9ca3af;">{{ $medicine->created_at->format('d M Y H:i') }}</td>
                            <td><a href="{{ route('admin.grosir.edit', $medicine->id) }}" class="btn btn-secondary btn-sm">Edit</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;color:#6b7280;padding:2rem;">Belum ada produk grosir.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Tab switching
function switchTab(tab) {
    const panels = ['retail', 'grosir'];
    panels.forEach(t => {
        document.getElementById('panel-' + t).style.display = t === tab ? 'block' : 'none';
        const btn = document.getElementById('tab-' + t);
        if (t === tab) {
            btn.style.borderBottomColor = t === 'retail' ? '#1E88E5' : '#7C3AED';
            btn.style.color = t === 'retail' ? '#1E88E5' : '#7C3AED';
        } else {
            btn.style.borderBottomColor = 'transparent';
            btn.style.color = '#6b7280';
        }
    });
}

// Simpan nilai awal
let prevRetail = {{ $totalMedicines }};
let prevGrosir = {{ $totalGrosir }};

function formatRp(n) {
    return 'Rp ' + Number(n).toLocaleString('id-ID');
}

function updateTime() {
    const now = new Date();
    document.getElementById('realtime-time').textContent =
        'Update: ' + now.toLocaleTimeString('id-ID');
}

async function fetchStats() {
    try {
        const res  = await fetch('{{ route("admin.dashboard.stats") }}');
        const data = await res.json();

        // Update badge di tab
        document.getElementById('badge-retail').textContent = data.retail;
        document.getElementById('badge-grosir').textContent = data.grosir;

        // Update angka stats
        document.getElementById('stat-retail').textContent  = data.retail;
        document.getElementById('stat-grosir').textContent  = data.grosir;
        document.getElementById('stat-total').textContent   = data.total;
        document.getElementById('stat-lowstok').textContent = data.lowStok;

        // Tampilkan badge produk baru
        const newRetail = data.retail - prevRetail;
        const newGrosir = data.grosir - prevGrosir;

        const retBadge = document.getElementById('stat-retail-new');
        const grosBadge = document.getElementById('stat-grosir-new');

        if (newRetail > 0) {
            retBadge.textContent = '+' + newRetail + ' baru';
            retBadge.style.display = 'inline';
        } else {
            retBadge.style.display = 'none';
        }

        if (newGrosir > 0) {
            grosBadge.textContent = '+' + newGrosir + ' baru';
            grosBadge.style.display = 'inline';
        } else {
            grosBadge.style.display = 'none';
        }

        // Update tabel retail jika ada perubahan
        if (data.retail !== prevRetail) {
            updateRetailTable(data.latestRetail);
            prevRetail = data.retail;
        }

        // Update tabel grosir jika ada perubahan
        if (data.grosir !== prevGrosir) {
            updateGrosirTable(data.latestGrosir);
            prevGrosir = data.grosir;
        }

        document.getElementById('realtime-status').textContent = 'Realtime aktif';
        document.getElementById('realtime-dot').style.background = '#10b981';
        updateTime();

    } catch (e) {
        document.getElementById('realtime-status').textContent = 'Gagal memuat data';
        document.getElementById('realtime-dot').style.background = '#ef4444';
    }
}

function updateRetailTable(items) {
    if (!items || items.length === 0) return;
    const tbody = document.getElementById('retail-tbody');
    if (!tbody) return;
    tbody.innerHTML = items.map(m => `
        <tr style="animation:fadeIn 0.4s ease;">
            <td><strong>${m.nama_obat}</strong></td>
            <td>${m.kategori}</td>
            <td>${formatRp(m.harga)}</td>
            <td><span style="color:${m.stok > 10 ? '#10b981' : m.stok > 0 ? '#f59e0b' : '#ef4444'};">
                <strong>${m.stok > 0 ? m.stok : 'Habis'}</strong></span></td>
            <td style="font-size:0.82rem;color:#9ca3af;">${m.created_at}</td>
            <td><a href="/admin/medicines/${m.id}/edit" class="btn btn-secondary btn-sm">Edit</a></td>
        </tr>`).join('');
}

function updateGrosirTable(items) {
    if (!items || items.length === 0) return;
    const tbody = document.getElementById('grosir-tbody');
    if (!tbody) return;
    tbody.innerHTML = items.map(m => `
        <tr style="animation:fadeIn 0.4s ease;">
            <td><strong>${m.nama_obat}</strong></td>
            <td>${m.kategori}</td>
            <td>${formatRp(m.harga)}</td>
            <td><span style="color:${m.stok > 10 ? '#10b981' : m.stok > 0 ? '#f59e0b' : '#ef4444'};">
                <strong>${m.stok > 0 ? m.stok : 'Habis'}</strong></span></td>
            <td style="font-size:0.82rem;color:#9ca3af;">${m.created_at}</td>
            <td><a href="/admin/grosir/${m.id}/edit" class="btn btn-secondary btn-sm">Edit</a></td>
        </tr>`).join('');
}

// Jalankan setiap 10 detik
fetchStats();
setInterval(fetchStats, 10000);
</script>
<style>
@keyframes fadeIn {
    from { opacity:0; transform:translateY(-4px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>
@endsection
