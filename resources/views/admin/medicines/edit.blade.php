@extends('layouts.admin')

@section('title', 'Edit Obat - Admin Medikpedia')
@section('page-title', '✏️ Edit Obat: ' . $medicine->nama_obat)

@section('content')
<div class="card">
    <form action="{{ route('admin.medicines.update', $medicine->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Left Column -->
            <div>
                <div class="form-group">
                    <label class="form-label" for="nama_obat">Nama Obat*</label>
                    <input 
                        type="text" 
                        id="nama_obat"
                        name="nama_obat" 
                        class="form-control @error('nama_obat') is-invalid @enderror"
                        placeholder="Contoh: Paracetamol 500mg"
                        required
                        value="{{ old('nama_obat', $medicine->nama_obat) }}"
                    >
                    @error('nama_obat')
                        <span class="form-errors">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="kategori">Perusahaan*</label>
                    <select 
                        id="kategori"
                        name="kategori" 
                        class="form-control @error('kategori') is-invalid @enderror"
                        required
                    >
                        <option value="">-- Pilih Perusahaan --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected(old('kategori', $medicine->kategori) == $category)>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <span class="form-errors">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
    <label class="form-label">Harga (Rp) <span class="req">*</span></label>
    <input type="number" name="harga"
           class="form-input {{ $errors->has('harga') ? 'is-invalid' : '' }}"
           placeholder="50000" 
           step="1" 
           min="0"
           value="{{ old('harga') }}" required>
    @error('harga')
        <div class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
    @enderror
</div>

                    <div class="form-group">
                        <label class="form-label" for="stok">Stok*</label>
                        <input 
                            type="number" 
                            id="stok"
                            name="stok" 
                            class="form-control @error('stok') is-invalid @enderror"
                            placeholder="100"
                            min="0"
                            required
                            value="{{ old('stok', $medicine->stok) }}"
                        >
                        @error('stok')
                            <span class="form-errors">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsi">Deskripsi*</label>
                    <textarea 
                        id="deskripsi"
                        name="deskripsi" 
                        class="form-control @error('deskripsi') is-invalid @enderror"
                        placeholder="Deskripsikan obat ini secara detail..."
                        required
                    >{{ old('deskripsi', $medicine->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <span class="form-errors">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div class="form-group">
                    <label class="form-label" for="gambar">Gambar Obat</label>
                    
                    @if($medicine->gambar)
                        <div style="margin-bottom: 1rem;">
                            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Gambar saat ini:</p>
                            <img src="{{ asset('storage/' . $medicine->gambar) }}" alt="{{ $medicine->nama_obat }}" style="
                                width: 100%;
                                max-height: 200px;
                                object-fit: contain;
                                border-radius: 0.5rem;
                                border: 1px solid #e5e7eb;
                            ">
                            <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.5rem;">
                                Upload gambar baru untuk mengganti
                            </p>
                        </div>
                    @endif

                    <div style="
                        border: 2px dashed #d1d5db;
                        border-radius: 0.5rem;
                        padding: 2rem;
                        text-align: center;
                        transition: all 0.3s;
                        cursor: pointer;
                        background: #f9fafb;
                    " id="dropZone">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📸</div>
                        <p style="color: #6b7280; margin-bottom: 0.5rem;">
                            Drag & drop gambar di sini
                        </p>
                        <p style="color: #9ca3af; font-size: 0.875rem;">
                            atau
                        </p>
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('gambar').click()">
                            Pilih Gambar
                        </button>
                        <input 
                            type="file" 
                            id="gambar"
                            name="gambar" 
                            class="form-control @error('gambar') is-invalid @enderror"
                            accept="image/*"
                            style="display: none;"
                        >
                        <p style="color: #9ca3af; font-size: 0.75rem; margin-top: 1rem;">
                            Ukuran max: 10MB | Format: JPG, PNG, GIF
                        </p>
                    </div>
                    @error('gambar')
                        <span class="form-errors">{{ $message }}</span>
                    @enderror

                    <!-- Image Preview -->
                    <div id="imagePreview" style="margin-top: 1rem; display: none;">
                        <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;">Preview gambar baru:</p>
                        <img id="previewImg" src="" alt="Preview" style="
                            width: 100%;
                            max-height: 300px;
                            object-fit: contain;
                            border-radius: 0.5rem;
                            border: 1px solid #e5e7eb;
                        ">
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
            <button type="submit" class="btn btn-primary">
                ✅ Simpan Perubahan
            </button>
            <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
                ❌ Batal
            </a>
        </div>
    </form>
</div>

<script>
    // Image preview
    document.getElementById('gambar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('previewImg').src = event.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Drag and drop
    const dropZone = document.getElementById('dropZone');
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.style.borderColor = '#10b981';
        dropZone.style.background = '#f0fdf4';
    }

    function unhighlight(e) {
        dropZone.style.borderColor = '#d1d5db';
        dropZone.style.background = '#f9fafb';
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('gambar').files = files;

        // Trigger change event
        const event = new Event('change', { bubbles: true });
        document.getElementById('gambar').dispatchEvent(event);
    }
</script>
@endsection
