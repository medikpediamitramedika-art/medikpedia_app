@extends('layouts.admin')

@section('title', 'Edit Aktivitas - Admin Medikpedia')
@section('page-title', '✏️ Edit Aktivitas')

@section('content')
<div class="card" style="max-width:600px;">
    <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="form-group">
            <label class="form-label">Judul Aktivitas *</label>
            <input type="text" name="judul" class="form-control"
                   value="{{ old('judul', $activity->judul) }}" required>
            @error('judul')<span class="form-errors">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Aktivitas *</label>
            <input type="date" name="tanggal" class="form-control"
                   value="{{ old('tanggal', $activity->tanggal->format('Y-m-d')) }}" required>
            @error('tanggal')<span class="form-errors">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi <span style="color:#6b7280;font-weight:400;">(opsional)</span></label>
            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $activity->deskripsi) }}</textarea>
            @error('deskripsi')<span class="form-errors">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Foto Aktivitas <span style="color:#6b7280;font-weight:400;">(kosongkan jika tidak ingin mengubah)</span></label>

            {{-- Foto saat ini --}}
            <div style="margin-bottom:0.75rem;">
                <p style="font-size:0.8rem;color:#6b7280;margin-bottom:0.4rem;">Foto saat ini:</p>
                <img src="{{ asset('storage/' . $activity->foto) }}" alt="{{ $activity->judul }}"
                     style="max-height:160px;max-width:100%;border-radius:0.5rem;border:1px solid #e5e7eb;object-fit:cover;">
            </div>

            <div id="dropZone" onclick="document.getElementById('fotoInput').click()"
                 style="border:2px dashed #d1d5db;border-radius:0.5rem;padding:1.5rem;text-align:center;cursor:pointer;background:#f9fafb;transition:all 0.2s;">
                <p style="color:#374151;font-weight:600;margin:0 0 0.25rem;">Klik untuk ganti foto</p>
                <p id="fotoLabel" style="color:#9ca3af;font-size:0.875rem;margin:0;">JPG, PNG, GIF, WEBP — Maks. 5MB</p>
            </div>
            <input type="file" id="fotoInput" name="foto" accept="image/*" style="display:none;"
                   onchange="previewFoto(this)">
            <div id="preview" style="margin-top:0.75rem;display:none;">
                <img id="previewImg" src="" alt="Preview"
                     style="max-height:160px;max-width:100%;border-radius:0.5rem;border:1px solid #e5e7eb;object-fit:cover;">
            </div>
            @error('foto')<span class="form-errors">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                <input type="checkbox" name="is_published" value="1"
                       {{ old('is_published', $activity->is_published) ? 'checked' : '' }}
                       style="width:16px;height:16px;">
                <span style="font-weight:600;">Tampilkan di halaman publik</span>
            </label>
        </div>

        <div style="display:flex;gap:1rem;padding-top:1rem;border-top:1px solid #e5e7eb;">
            <button type="submit" class="btn btn-primary">✅ Simpan Perubahan</button>
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
</script>
@endsection
