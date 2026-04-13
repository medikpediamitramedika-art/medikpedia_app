@extends('layouts.admin')

@section('title', 'Manajemen Aktivitas - Admin Medikpedia')
@section('page-title', '📸 Manajemen Aktivitas')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.75rem;">
    <p style="color:#6b7280;margin:0;">Total: <strong>{{ $activities->total() }} aktivitas</strong></p>
    <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">➕ Tambah Aktivitas</a>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.activities.index') }}"
      style="background:white;padding:1rem 1.25rem;border-radius:0.75rem;margin-bottom:1.5rem;display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
    <div style="flex:1;min-width:200px;">
        <label style="font-size:0.8rem;color:#6b7280;display:block;margin-bottom:0.3rem;">Cari Aktivitas</label>
        <input type="text" name="search" value="{{ $search }}"
               placeholder="Judul atau deskripsi..."
               style="width:100%;padding:0.5rem 0.75rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:0.9rem;outline:none;"
               onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
    </div>
    <div style="display:flex;gap:0.5rem;">
        <button type="submit" class="btn btn-primary" style="padding:0.5rem 1.25rem;">Cari</button>
        @if($search)
            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary" style="padding:0.5rem 1rem;">✕ Reset</a>
        @endif
    </div>
</form>

@if($activities->count() > 0)
    {{-- Grid foto --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1.25rem;margin-bottom:1.5rem;">
        @foreach($activities as $activity)
            <div style="background:white;border-radius:0.75rem;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.1);border:1px solid #e5e7eb;">
                <div style="position:relative;height:180px;overflow:hidden;">
                    <img src="{{ asset('storage/' . $activity->foto) }}"
                         alt="{{ $activity->judul }}"
                         style="width:100%;height:100%;object-fit:cover;">
                    <div style="position:absolute;top:0.5rem;right:0.5rem;">
                        @if($activity->is_published)
                            <span style="background:#d1fae5;color:#065f46;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;font-weight:600;">✓ Aktif</span>
                        @else
                            <span style="background:#fee2e2;color:#7f1d1d;padding:0.2rem 0.6rem;border-radius:20px;font-size:0.75rem;font-weight:600;">✕ Draft</span>
                        @endif
                    </div>
                </div>
                <div style="padding:0.9rem;">
                    <p style="font-weight:700;color:#1f2937;margin:0 0 0.25rem;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $activity->judul }}</p>
                    <p style="font-size:0.8rem;color:#6b7280;margin:0 0 0.75rem;">📅 {{ $activity->tanggal->format('d M Y') }}</p>
                    <div style="display:flex;gap:0.5rem;">
                        <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn btn-secondary btn-sm" style="flex:1;text-align:center;">✏️ Edit</a>
                        <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" style="flex:1;" onsubmit="return confirm('Yakin hapus aktivitas ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="width:100%;">🗑️ Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div style="color:#6b7280;font-size:0.875rem;">
            Menampilkan {{ $activities->firstItem() }}–{{ $activities->lastItem() }} dari {{ $activities->total() }} aktivitas
        </div>
        <div style="display:flex;gap:0.35rem;align-items:center;">
            @if($activities->onFirstPage())
                <span style="padding:0.4rem 0.75rem;border-radius:0.4rem;background:#f3f4f6;color:#d1d5db;font-size:0.875rem;cursor:not-allowed;">‹</span>
            @else
                <a href="{{ $activities->previousPageUrl() }}" style="padding:0.4rem 0.75rem;border-radius:0.4rem;background:white;color:#374151;font-size:0.875rem;text-decoration:none;border:1px solid #e5e7eb;" onmouseover="this.style.background='#1E88E5';this.style.color='white'" onmouseout="this.style.background='white';this.style.color='#374151'">‹</a>
            @endif
            @foreach($activities->getUrlRange(1, $activities->lastPage()) as $page => $url)
                @if($page == $activities->currentPage())
                    <span style="padding:0.4rem 0.75rem;border-radius:0.4rem;background:#1E88E5;color:white;font-size:0.875rem;font-weight:600;min-width:36px;text-align:center;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding:0.4rem 0.75rem;border-radius:0.4rem;background:white;color:#374151;font-size:0.875rem;text-decoration:none;border:1px solid #e5e7eb;min-width:36px;text-align:center;" onmouseover="this.style.background='#1E88E5';this.style.color='white'" onmouseout="this.style.background='white';this.style.color='#374151'">{{ $page }}</a>
                @endif
            @endforeach
            @if($activities->hasMorePages())
                <a href="{{ $activities->nextPageUrl() }}" style="padding:0.4rem 0.75rem;border-radius:0.4rem;background:white;color:#374151;font-size:0.875rem;text-decoration:none;border:1px solid #e5e7eb;" onmouseover="this.style.background='#1E88E5';this.style.color='white'" onmouseout="this.style.background='white';this.style.color='#374151'">›</a>
            @else
                <span style="padding:0.4rem 0.75rem;border-radius:0.4rem;background:#f3f4f6;color:#d1d5db;font-size:0.875rem;cursor:not-allowed;">›</span>
            @endif
        </div>
    </div>
@else
    <div style="background:white;padding:3rem;border-radius:0.75rem;text-align:center;color:#6b7280;">
        @if($search)
            <div style="font-size:2rem;margin-bottom:1rem;">🔍</div>
            <p>Tidak ada aktivitas yang cocok dengan "<strong>{{ $search }}</strong>".</p>
            <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary" style="margin-top:1rem;">✕ Hapus Filter</a>
        @else
            <div style="font-size:2rem;margin-bottom:1rem;">📸</div>
            <p>Belum ada aktivitas.</p>
            <a href="{{ route('admin.activities.create') }}" class="btn btn-primary" style="margin-top:1rem;">➕ Tambah Aktivitas Pertama</a>
        @endif
    </div>
@endif
@endsection
