@extends('layouts.admin')

@section('title', 'Tambah Aktivitas - Admin Medikpedia')
@section('page-title', '➕ Tambah Aktivitas')

@section('content')
<div class="card" style="max-width:600px;">
    <form action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Judul Aktivitas *</label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul') }}"
                   placeholder="Contoh: Kunjungan ke Apotek Kimia Farma Kemayoran" required>
            @error('judul')<span class="form-errors">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Aktivitas *</label>
            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', date('Y-m-d')) }}" required>
            @error('tanggal')<span class="form-errors">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi <span style="color:#6b7280;font-weight:400;">(opsional)</span></label>
            <textarea name="deskripsi" class="form-control" rows="3"
                      placeholder="Keterangan singkat aktivitas...">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')<span class="form-errors">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Foto Aktivitas *</label>
            <div id="dropZone" onclick="document.getElementById('fotoInput').click()"
                 style="border:2px dashed #d1d5db;border-radius:0.5rem;padding:2rem;text-align:center;cursor:pointer;background:#f9fafb;transition:all 0.2s;">
                <div style="font-size:2rem;margin-bottom:0.5rem;">📷</div>
                <p style="color:#374151;font-weight:600;margin:0 0 0.25rem;">Klik atau drag & drop foto</p>
                <p id="fotoLabel" style="color:#9ca3af;font-size:0.875rem;margin:0;">JPG, PNG, GIF, WEBP — Maks. 5MB</p>
            </div>
            <input type="file" id="fotoInput" name="foto" accept="image/*" style="display:none;"
                   onchange="previewFoto(this)" required>
            <div id="preview" style="margin-top:0.75rem;display:none;">
                <img id="previewImg" src="" alt="Preview"
                     style="max-height:200px;max-width:100%;border-radius:0.5rem;border:1px solid #e5e7eb;object-fit:cover;">
            </div>
            @error('foto')<span class="form-errors">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', '1') ? 'checked' : '' }}
                       style="width:16px;height:16px;">
                <span style="font-weight:600;">Tampilkan di halaman publik</span>
            </label>
        </div>

        <div style="display:flex;gap:1rem;padding-top:1rem;border-top:1px solid #e5e7eb;">
            <button type="submit" class="btn btn-primary">✅ Simpan</button>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">❌ Batal</a>
        </div>
    </form>
</div>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('preview').style.display = 'block';
            document.getElementById('fotoLabel').textContent = '✅ ' + input.files[0].name;
            document.getElementById('fotoLabel').style.color = '#059669';
            document.getElementById('dropZone').style.borderColor = '#059669';
            document.getElementById('dropZone').style.background = '#f0fdf4';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

const dz = document.getElementById('dropZone');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.style.borderColor='#3b82f6'; dz.style.background='#eff6ff'; });
dz.addEventListener('dragleave', () => { dz.style.borderColor='#d1d5db'; dz.style.background='#f9fafb'; });
dz.addEventListener('drop', e => {
    e.preventDefault();
    const input = document.getElementById('fotoInput');
    input.files = e.dataTransfer.files;
    previewFoto(input);
});
</script>
@endsection
