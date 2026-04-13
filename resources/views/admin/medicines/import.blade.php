@extends('layouts.admin')

@section('title', 'Import Obat - Admin Medikpedia')
@section('page-title', '📥 Import Data Obat')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">

    {{-- Info Card --}}
    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 0.75rem; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;">
        <h3 style="color: #1d4ed8; margin: 0 0 0.75rem; font-size: 1rem;">📋 Petunjuk Import</h3>
        <ol style="color: #1e40af; margin: 0; padding-left: 1.25rem; line-height: 1.8;">
            <li>Download template Excel di bawah</li>
            <li>Buka file dengan <strong>Microsoft Excel</strong></li>
            <li>Isi data obat di sheet <strong>"Data Obat"</strong></li>
            <li>Simpan file — <strong>tetap simpan sebagai .xls</strong></li>
            <li>Upload file langsung di form ini</li>
        </ol>
        <p style="color: #059669; margin: 0.75rem 0 0; font-size: 0.875rem; font-weight: 600;">
            ✅ File template yang didownload bisa langsung diupload kembali setelah diisi.
        </p>
    </div>

    {{-- Download Template --}}
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 1rem; font-size: 1rem; color: #374151;">📄 Template File</h3>

        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('admin.medicines.import.template') }}" class="btn btn-secondary">
                ⬇️ Download Template Excel (.xls)
            </a>
            <span style="color: #6b7280; font-size: 0.875rem;">
                Langsung rapi saat dibuka di Microsoft Excel
            </span>
        </div>

        <div style="margin-top: 1rem; background: #f9fafb; border-radius: 0.5rem; padding: 1rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">
                <thead>
                    <tr style="background: #e5e7eb;">
                        <th style="padding: 0.4rem 0.75rem; text-align: left; border: 1px solid #d1d5db;">nama_obat</th>
                        <th style="padding: 0.4rem 0.75rem; text-align: left; border: 1px solid #d1d5db;">perusahaan</th>
                        <th style="padding: 0.4rem 0.75rem; text-align: left; border: 1px solid #d1d5db;">harga</th>
                        <th style="padding: 0.4rem 0.75rem; text-align: left; border: 1px solid #d1d5db;">stok</th>
                        <th style="padding: 0.4rem 0.75rem; text-align: left; border: 1px solid #d1d5db;">deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 0.4rem 0.75rem; border: 1px solid #d1d5db; color: #6b7280;">Paracetamol 500mg</td>
                        <td style="padding: 0.4rem 0.75rem; border: 1px solid #d1d5db; color: #6b7280;">KIMIA FARMA</td>
                        <td style="padding: 0.4rem 0.75rem; border: 1px solid #d1d5db; color: #6b7280;">5000</td>
                        <td style="padding: 0.4rem 0.75rem; border: 1px solid #d1d5db; color: #6b7280;">100</td>
                        <td style="padding: 0.4rem 0.75rem; border: 1px solid #d1d5db; color: #6b7280;">Obat pereda nyeri...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 0.75rem; font-size: 0.8rem; color: #6b7280;">
            <strong>Perusahaan yang tersedia:</strong>
            {{ implode(', ', $categories) }}
        </div>
    </div>

    {{-- Upload Form --}}
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 1.25rem; font-size: 1rem; color: #374151;">📤 Upload File</h3>

        @if($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem; color: #7f1d1d; font-size: 0.875rem;">
                ❌ {{ $errors->first('file') }}
            </div>
        @endif

        <form action="{{ route('admin.medicines.import.process') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Drop Zone --}}
            <div id="dropZone" style="
                border: 2px dashed #d1d5db;
                border-radius: 0.75rem;
                padding: 2.5rem;
                text-align: center;
                cursor: pointer;
                transition: all 0.2s;
                margin-bottom: 1.25rem;
                background: #f9fafb;
            " onclick="document.getElementById('fileInput').click()"
               ondragover="handleDragOver(event)"
               ondragleave="handleDragLeave(event)"
               ondrop="handleDrop(event)">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📂</div>
                <p style="color: #374151; font-weight: 600; margin: 0 0 0.25rem;">Klik atau drag & drop file di sini</p>
                <p style="color: #9ca3af; font-size: 0.875rem; margin: 0;" id="fileLabel">Format: CSV, XLS, XLSX — Maks. 2MB</p>
            </div>

            <input type="file" id="fileInput" name="file" accept=".csv,.xls,.xlsx,text/csv"
                   style="display: none;" onchange="updateFileLabel(this)">

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; flex-wrap: wrap;">
                <a href="{{ route('admin.medicines.index') }}" class="btn btn-secondary">
                    ← Kembali
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled
                        style="opacity: 0.5; cursor: not-allowed;">
                    📥 Import Sekarang
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function updateFileLabel(input) {
    const label = document.getElementById('fileLabel');
    const btn   = document.getElementById('submitBtn');
    const zone  = document.getElementById('dropZone');

    if (input.files && input.files[0]) {
        const name = input.files[0].name;
        label.textContent = '✅ ' + name;
        label.style.color = '#059669';
        zone.style.borderColor = '#059669';
        zone.style.background  = '#f0fdf4';
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor  = 'pointer';
    }
}

function handleDragOver(e) {
    e.preventDefault();
    const zone = document.getElementById('dropZone');
    zone.style.borderColor = '#3b82f6';
    zone.style.background  = '#eff6ff';
}

function handleDragLeave(e) {
    const zone = document.getElementById('dropZone');
    zone.style.borderColor = '#d1d5db';
    zone.style.background  = '#f9fafb';
}

function handleDrop(e) {
    e.preventDefault();
    const input = document.getElementById('fileInput');
    const dt    = e.dataTransfer;
    if (dt.files.length) {
        input.files = dt.files;
        updateFileLabel(input);
    }
}
</script>
@endsection
