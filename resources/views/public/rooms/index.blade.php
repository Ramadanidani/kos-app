@extends('layouts.public')

@section('title', 'Daftar Kamar')

@push('styles')
<style>
    .form-select {
        background-color: var(--bg-main) !important;
        color: var(--text-white) !important;
        border-color: rgba(255,255,255,0.15) !important;
    }
    .form-select option {
        background-color: var(--bg-main);
        color: var(--text-white);
    }
    .form-select:focus {
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 3px rgba(255,140,50,0.2) !important;
    }
    .room-card-hover:hover img {
        transform: scale(1.04);
    }
    .room-card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0,0,0,.4) !important;
    }

    /* Pagination dark */
    .pagination { gap: 4px; }
    .page-link {
        background: var(--bg-card) !important;
        border-color: rgba(255,255,255,0.1) !important;
        color: var(--text-muted) !important;
        border-radius: 7px !important;
    }
    .page-link:hover {
        background: rgba(255,140,50,0.15) !important;
        color: var(--accent) !important;
        border-color: rgba(255,140,50,0.3) !important;
    }
    .page-item.active .page-link {
        background: var(--accent) !important;
        border-color: var(--accent) !important;
        color: #fff !important;
    }
    .page-item.disabled .page-link { opacity: .4; }
</style>
@endpush

@section('content')
<div class="container py-5">

    {{-- HEADER --}}
    <div class="mb-4">
        <h2 style="font-weight:700; color:var(--text-white); margin-bottom:.4rem;">
            <i class="bi bi-grid-3x3-gap-fill me-2" style="color:var(--accent);"></i>
            Daftar Kamar
        </h2>
        <p style="color:var(--text-muted); margin:0;">
            Temukan kamar terbaik sesuai kebutuhanmu.
        </p>
    </div>

    {{-- STATS --}}
    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card-mk text-center p-3">
                <h3 style="color:var(--accent); font-weight:700; margin:0;">{{ $totalRooms }}</h3>
                <small style="color:var(--text-muted);">Total</small>
            </div>
        </div>
        <div class="col-4">
            <div class="card-mk text-center p-3">
                <h3 style="color:#4ade80; font-weight:700; margin:0;">{{ $availableRooms }}</h3>
                <small style="color:var(--text-muted);">Tersedia</small>
            </div>
        </div>
        <div class="col-4">
            <div class="card-mk text-center p-3">
                <h3 style="color:#f87171; font-weight:700; margin:0;">{{ $occupiedRooms }}</h3>
                <small style="color:var(--text-muted);">Terisi</small>
            </div>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="card-mk p-4 mb-5">
        <form method="GET" action="{{ route('rooms.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label" style="color:var(--text-muted); font-size:.85rem;">
                        Status Kamar
                    </label>
                    <select name="status" class="form-select">
                        <option value="all">Semua Status</option>
                        <option value="available"   {{ request('status') == 'available'   ? 'selected' : '' }}>Tersedia</option>
                        <option value="occupied"    {{ request('status') == 'occupied'    ? 'selected' : '' }}>Terisi</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label class="form-label" style="color:var(--text-muted); font-size:.85rem;">
                        Tipe Kamar
                    </label>
                    <select name="type" class="form-select">
                        <option value="all">Semua Tipe</option>
                        <option value="Standard" {{ request('type') == 'Standard' ? 'selected' : '' }}>Standard</option>
                        <option value="Deluxe"   {{ request('type') == 'Deluxe'   ? 'selected' : '' }}>Deluxe</option>
                        <option value="VIP"      {{ request('type') == 'VIP'      ? 'selected' : '' }}>VIP</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <button type="submit" class="btn w-100"
                            style="background:var(--accent); color:white; border:none;
                                   padding:11px; border-radius:10px; font-weight:600;">
                        <i class="bi bi-funnel-fill me-1"></i> Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- GRID KAMAR --}}
    <div class="row g-4 align-items-stretch">
        @forelse($rooms as $room)
        <div class="col-md-6 col-xl-4 d-flex">
            <div class="card-mk overflow-hidden w-100 d-flex flex-column room-card-hover"
                 style="border-radius:18px; transition:.25s;">

                {{-- FOTO --}}
                <div style="overflow:hidden; flex-shrink:0;">
                    @if($room->primaryPhoto)
                        <img src="{{ asset('storage/' . $room->primaryPhoto->photo_path) }}"
                             alt="{{ $room->name }}"
                             style="width:100%; height:220px; object-fit:cover; transition:.35s; display:block;">
                    @else
                        <div style="width:100%; height:220px; background:#1a2d40;
                                    display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-image" style="font-size:3rem; color:rgba(255,255,255,0.1);"></i>
                        </div>
                    @endif
                </div>

                {{-- BODY --}}
                <div class="p-4 d-flex flex-column flex-grow-1">

                    {{-- Nama & Badge --}}
                    <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                        <div>
                            <h5 style="color:var(--text-white); font-weight:700; margin-bottom:4px;">
                                {{ $room->name }}
                            </h5>
                            <p style="color:var(--text-muted); font-size:.82rem; margin:0;">
                                {{ $room->type }}
                            </p>
                        </div>
                        <span class="badge rounded-pill badge-{{ $room->status }}"
                              style="white-space:nowrap; font-size:.72rem; padding:5px 10px;">
                            {{ match($room->status) {
                                'available'   => 'Tersedia',
                                'occupied'    => 'Terisi',
                                'maintenance' => 'Maintenance',
                                default       => ucfirst($room->status)
                            } }}
                        </span>
                    </div>

                    {{-- Meta --}}
                    <div class="d-flex flex-wrap gap-3 mb-3"
                         style="font-size:.82rem; color:var(--text-muted);">
                        <span><i class="bi bi-aspect-ratio me-1"></i>{{ $room->size }} m²</span>
                        <span><i class="bi bi-building me-1"></i>Lantai {{ $room->floor }}</span>
                    </div>

                    {{-- Deskripsi --}}
                    <p style="color:var(--text-muted); font-size:.9rem; line-height:1.7; flex-grow:1;">
                        {{ $room->description ? Str::limit($room->description, 90) : 'Kamar nyaman dengan fasilitas lengkap.' }}
                    </p>

                    {{-- Fasilitas --}}
                    @if($room->facilities && count($room->facilities) > 0)
                    <div class="mb-4 d-flex flex-wrap gap-2">
                        @foreach(array_slice($room->facilities, 0, 3) as $facility)
                        <span style="font-size:.72rem; padding:5px 10px;
                                     background:rgba(255,255,255,0.05);
                                     border:1px solid rgba(255,255,255,0.08);
                                     border-radius:999px; color:var(--text-muted);">
                            <i class="bi bi-check2 me-1" style="color:var(--accent);"></i>{{ $facility }}
                        </span>
                        @endforeach
                        @if(count($room->facilities) > 3)
                        <span style="font-size:.72rem; padding:5px 10px;
                                     background:rgba(255,255,255,0.05);
                                     border:1px solid rgba(255,255,255,0.08);
                                     border-radius:999px; color:var(--text-muted);">
                            +{{ count($room->facilities) - 3 }} lainnya
                        </span>
                        @endif
                    </div>
                    @endif

                    {{-- Harga & Tombol --}}
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <div>
                            <div style="color:var(--accent); font-weight:700; font-size:1.1rem;">
                                Rp {{ number_format($room->price, 0, ',', '.') }}
                            </div>
                            <small style="color:var(--text-muted);">/ bulan</small>
                        </div>
                        <a href="{{ route('rooms.show', $room) }}" class="btn"
                           style="background:rgba(255,140,50,0.12); color:var(--accent);
                                  border-radius:10px; padding:10px 16px;
                                  font-size:.85rem; font-weight:600;
                                  border:1px solid rgba(255,140,50,0.25);
                                  transition:background .2s;"
                           onmouseover="this.style.background='rgba(255,140,50,0.22)'"
                           onmouseout="this.style.background='rgba(255,140,50,0.12)'">
                            Detail
                        </a>
                    </div>

                </div>
            </div>
        </div>

        @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-house-x" style="font-size:4rem; color:var(--text-muted);"></i>
            <h5 style="color:var(--text-white); margin-top:1rem;">Kamar Tidak Ditemukan</h5>
            <p style="color:var(--text-muted);">Coba ubah filter pencarian kamu.</p>
            <a href="{{ route('rooms.index') }}"
               style="color:var(--accent); font-size:.85rem; text-decoration:none;">
                Lihat semua kamar →
            </a>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($rooms->hasPages())
    <div class="mt-5 d-flex justify-content-center">
        {{ $rooms->withQueryString()->links() }}
    </div>
    @endif

</div>
@endsection