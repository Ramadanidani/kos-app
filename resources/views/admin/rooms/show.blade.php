@extends('layouts.admin')

@section('title', $room->name)
@section('page-title', 'Detail Kamar')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.rooms.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">{{ $room->name }}</span>

    {{-- Tombol Edit --}}
    <a href="{{ route('admin.rooms.edit', $room) }}"
       style="margin-left:auto; background:rgba(255,140,50,0.12); color:var(--accent);
              border:1px solid rgba(255,140,50,0.25); border-radius:10px;
              padding:7px 16px; font-size:.85rem; text-decoration:none;
              display:inline-flex; align-items:center; gap:6px;">
        <i class="bi bi-pencil"></i> Edit Kamar
    </a>

    {{-- Tombol Hapus --}}
    <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
          onsubmit="return confirm('Hapus kamar {{ $room->name }}? Semua foto akan ikut terhapus.')">
        @csrf @method('DELETE')
        <button type="submit"
                style="background:rgba(239,68,68,0.1); color:#f87171;
                       border:1px solid rgba(239,68,68,0.2); border-radius:10px;
                       padding:7px 16px; font-size:.85rem; cursor:pointer;
                       display:inline-flex; align-items:center; gap:6px;">
            <i class="bi bi-trash"></i> Hapus
        </button>
    </form>
</div>

<div class="row g-4">

    {{-- KIRI: Foto --}}
    <div class="col-lg-7">

        {{-- Carousel --}}
        @if($room->photos->count() > 0)
        <div id="roomCarousel" class="carousel slide"
             data-bs-ride="carousel"
             style="border-radius:14px; overflow:hidden; margin-bottom:1rem;">
            <div class="carousel-inner">
                @foreach($room->photos as $i => $photo)
                <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $photo->photo_path) }}"
                         class="d-block w-100"
                         style="height:360px; object-fit:cover;"
                         alt="Foto {{ $i + 1 }}">
                    {{-- Badge foto utama --}}
                    @if($photo->is_primary)
                    <div style="position:absolute; top:12px; left:12px;
                                background:var(--accent); color:#fff;
                                font-size:.72rem; padding:3px 10px;
                                border-radius:20px; font-weight:600;">
                        Foto Utama
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @if($room->photos->count() > 1)
            <button class="carousel-control-prev" type="button"
                    data-bs-target="#roomCarousel" data-bs-slide="prev"
                    style="width:44px; height:44px; background:rgba(0,0,0,.5);
                           border-radius:50%; top:50%; transform:translateY(-50%);
                           left:12px; opacity:1;">
                <i class="bi bi-chevron-left" style="font-size:1rem;"></i>
            </button>
            <button class="carousel-control-next" type="button"
                    data-bs-target="#roomCarousel" data-bs-slide="next"
                    style="width:44px; height:44px; background:rgba(0,0,0,.5);
                           border-radius:50%; top:50%; transform:translateY(-50%);
                           right:12px; opacity:1;">
                <i class="bi bi-chevron-right" style="font-size:1rem;"></i>
            </button>
            @endif
        </div>

        {{-- Thumbnail strip --}}
        @if($room->photos->count() > 1)
        <div class="d-flex gap-2 flex-wrap">
            @foreach($room->photos as $i => $photo)
            <div style="position:relative; cursor:pointer;"
                 onclick="goToSlide({{ $i }}, this)">
                <img src="{{ asset('storage/' . $photo->photo_path) }}"
                     class="thumb-img {{ $i === 0 ? 'thumb-active' : '' }}"
                     style="width:72px; height:56px; object-fit:cover;
                            border-radius:8px; border:2px solid {{ $i === 0 ? 'var(--accent)' : 'transparent' }};
                            opacity:{{ $i === 0 ? '1' : '0.6' }}; transition:.2s;"
                     alt="Thumb {{ $i + 1 }}">
                @if($photo->is_primary)
                <div style="position:absolute; bottom:3px; left:3px;
                            background:var(--accent); color:#fff;
                            font-size:.55rem; padding:1px 5px; border-radius:4px;">
                    Utama
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        @else
        {{-- Placeholder jika tidak ada foto --}}
        <div style="height:360px; background:var(--bg-card);
                    border:1px solid rgba(255,255,255,0.07);
                    border-radius:14px; display:flex; flex-direction:column;
                    align-items:center; justify-content:center; gap:12px;">
            <i class="bi bi-image" style="font-size:4rem; color:rgba(255,255,255,0.1);"></i>
            <p style="color:var(--text-muted); font-size:.88rem; margin:0;">
                Belum ada foto untuk kamar ini.
            </p>
            <a href="{{ route('admin.rooms.edit', $room) }}"
               style="color:var(--accent); font-size:.85rem; text-decoration:none;">
                <i class="bi bi-plus-lg me-1"></i> Upload foto sekarang
            </a>
        </div>
        @endif

    </div>

    {{-- KANAN: Info Kamar --}}
    <div class="col-lg-5">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-info-circle me-2" style="color:var(--accent);"></i>
                    Informasi Kamar
                </h6>
                {{-- Badge Status --}}
                @php
                    $statusMap = [
                        'available'   => ['Tersedia',    '#4ade80', 'rgba(34,197,94,0.15)'],
                        'occupied'    => ['Terisi',      '#f87171', 'rgba(239,68,68,0.15)'],
                        'maintenance' => ['Maintenance', '#fbbf24', 'rgba(234,179,8,0.15)'],
                    ];
                    [$sLabel, $sColor, $sBg] = $statusMap[$room->status] ?? [ucfirst($room->status), '#fff', 'transparent'];
                @endphp
                <span style="font-size:.75rem; padding:4px 12px; border-radius:20px;
                             font-weight:600; color:{{ $sColor }}; background:{{ $sBg }};">
                    {{ $sLabel }}
                </span>
            </div>
            <div class="content-card-body">

                {{-- Nama & Harga --}}
                <div style="margin-bottom:1.25rem;">
                    <h4 style="color:var(--text-white); font-weight:700; margin-bottom:4px;">
                        {{ $room->name }}
                    </h4>
                    <div style="color:var(--accent); font-size:1.4rem; font-weight:700;">
                        Rp {{ number_format($room->price, 0, ',', '.') }}
                        <span style="color:var(--text-muted); font-size:.85rem; font-weight:400;">/bulan</span>
                    </div>
                </div>

                <hr style="border-color:rgba(255,255,255,0.07); margin-bottom:1.25rem;">

                {{-- Spesifikasi --}}
                <div class="d-flex flex-column gap-3 mb-4">

                    <div class="d-flex align-items-center gap-3">
                        <div style="width:38px; height:38px; background:rgba(255,140,50,0.1);
                                    border-radius:9px; display:flex; align-items:center;
                                    justify-content:center; flex-shrink:0;">
                            <i class="bi bi-tag-fill" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <div style="color:var(--text-muted); font-size:.72rem;">Tipe</div>
                            <div style="color:var(--text-white); font-weight:500;">{{ $room->type }}</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div style="width:38px; height:38px; background:rgba(255,140,50,0.1);
                                    border-radius:9px; display:flex; align-items:center;
                                    justify-content:center; flex-shrink:0;">
                            <i class="bi bi-aspect-ratio-fill" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <div style="color:var(--text-muted); font-size:.72rem;">Ukuran</div>
                            <div style="color:var(--text-white); font-weight:500;">{{ $room->size }} m²</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div style="width:38px; height:38px; background:rgba(255,140,50,0.1);
                                    border-radius:9px; display:flex; align-items:center;
                                    justify-content:center; flex-shrink:0;">
                            <i class="bi bi-building-fill" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <div style="color:var(--text-muted); font-size:.72rem;">Lantai</div>
                            <div style="color:var(--text-white); font-weight:500;">Lantai {{ $room->floor }}</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div style="width:38px; height:38px; background:rgba(255,140,50,0.1);
                                    border-radius:9px; display:flex; align-items:center;
                                    justify-content:center; flex-shrink:0;">
                            <i class="bi bi-images" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <div style="color:var(--text-muted); font-size:.72rem;">Jumlah Foto</div>
                            <div style="color:var(--text-white); font-weight:500;">
                                {{ $room->photos->count() }} foto
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Deskripsi --}}
                @if($room->description)
                <div style="margin-bottom:1.25rem;">
                    <div style="color:var(--text-muted); font-size:.75rem; margin-bottom:6px;">
                        Deskripsi
                    </div>
                    <p style="color:var(--text-white); font-size:.88rem;
                               line-height:1.7; margin:0;">
                        {{ $room->description }}
                    </p>
                </div>
                @endif

                {{-- Fasilitas --}}
                @if($room->facilities && count($room->facilities) > 0)
                <div>
                    <div style="color:var(--text-muted); font-size:.75rem; margin-bottom:8px;">
                        <i class="bi bi-lightning-fill me-1" style="color:var(--accent);"></i>
                        Fasilitas ({{ count($room->facilities) }})
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($room->facilities as $facility)
                        <span style="font-size:.75rem; padding:5px 11px;
                                     background:rgba(255,255,255,0.05);
                                     border:1px solid rgba(255,255,255,0.09);
                                     border-radius:999px; color:var(--text-muted);">
                            <i class="bi bi-check2 me-1" style="color:var(--accent);"></i>
                            {{ $facility }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

</div>

{{-- ── INFO PENGHUNI (jika kamar terisi) ── --}}
@if($room->status === 'occupied')
@php $currentTenant = $room->tenants()->where('status', 'active')->first(); @endphp
@if($currentTenant)
<div class="content-card mt-4">
    <div class="content-card-header">
        <h6 style="color:var(--text-white); font-weight:600; margin:0;">
            <i class="bi bi-person-fill me-2" style="color:var(--accent);"></i>
            Penghuni Saat Ini
        </h6>
        <a href="{{ route('admin.tenants.show', $currentTenant) }}"
           class="link-accent">Lihat Profil →</a>
    </div>
    <div class="content-card-body">
        <div class="d-flex align-items-center gap-3">
            <div style="width:48px; height:48px; background:rgba(255,140,50,0.15);
                        border-radius:50%; display:flex; align-items:center;
                        justify-content:center; color:var(--accent);
                        font-size:1.2rem; font-weight:700; flex-shrink:0;">
                {{ strtoupper(substr($currentTenant->name, 0, 1)) }}
            </div>
            <div style="flex:1;">
                <div style="color:var(--text-white); font-weight:600; font-size:.95rem;">
                    {{ $currentTenant->name }}
                </div>
                <div style="color:var(--text-muted); font-size:.8rem; margin-top:2px;">
                    <i class="bi bi-phone me-1"></i>{{ $currentTenant->phone }}
                    @if($currentTenant->start_date)
                    &nbsp;·&nbsp;
                    <i class="bi bi-calendar me-1"></i>Mulai {{ $currentTenant->start_date->format('d M Y') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif

{{-- ── CREATED / UPDATED INFO ── --}}
<div style="margin-top:1.5rem; text-align:right; font-size:.75rem; color:var(--text-muted);">
    Dibuat: {{ $room->created_at->format('d M Y, H:i') }}
    &nbsp;·&nbsp;
    Diperbarui: {{ $room->updated_at->format('d M Y, H:i') }}
</div>

@endsection

@push('scripts')
<script>
    function goToSlide(index, el) {
        const carousel = bootstrap.Carousel.getOrCreateInstance(
            document.getElementById('roomCarousel')
        );
        carousel.to(index);

        // Update thumbnail style
        document.querySelectorAll('.thumb-img').forEach((img, i) => {
            img.style.border = i === index
                ? '2px solid var(--accent)'
                : '2px solid transparent';
            img.style.opacity = i === index ? '1' : '0.6';
        });
    }

    // Sync thumbnail saat carousel bergerak otomatis
    document.getElementById('roomCarousel')?.addEventListener('slid.bs.carousel', function(e) {
        document.querySelectorAll('.thumb-img').forEach((img, i) => {
            img.style.border = i === e.to
                ? '2px solid var(--accent)'
                : '2px solid transparent';
            img.style.opacity = i === e.to ? '1' : '0.6';
        });
    });
</script>
@endpush