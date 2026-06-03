@extends('layouts.public')

@section('title', $room->name)

@push('styles')
<style>
    .breadcrumb-item a {
        color: var(--accent);
        text-decoration: none;
    }
    .breadcrumb-item.active {
        color: var(--text-muted);
    }
    .breadcrumb-item + .breadcrumb-item::before {
        color: var(--text-muted);
    }

    /* Carousel dark */
    .carousel-control-prev,
    .carousel-control-next {
        width: 44px;
        height: 44px;
        background: rgba(0,0,0,.5);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 1;
    }
    .carousel-control-prev { left: 12px; }
    .carousel-control-next { right: 12px; }

    /* Thumbnail foto */
    .thumb-item {
        width: 72px;
        height: 54px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid transparent;
        opacity: .6;
        transition: .2s;
    }
    .thumb-item.active,
    .thumb-item:hover {
        border-color: var(--accent);
        opacity: 1;
    }

    /* Kamar terkait card */
    .related-card {
        background: var(--bg-card);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 14px;
        overflow: hidden;
        transition: transform .25s, box-shadow .25s;
    }
    .related-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,.35);
    }
</style>
@endpush

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background:transparent; padding:0; margin:0;">
            <li class="breadcrumb-item">
                <a href="{{ url('/') }}">Beranda</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('rooms.index') }}">Kamar</a>
            </li>
            <li class="breadcrumb-item active">{{ $room->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- ── KOLOM KIRI: Foto ── --}}
        <div class="col-lg-7">

            {{-- Carousel --}}
            <div id="roomCarousel" class="carousel slide"
                 data-bs-ride="carousel"
                 style="border-radius:16px; overflow:hidden;">

                <div class="carousel-inner">
                    @if($room->photos->count() > 0)
                        @foreach($room->photos as $i => $photo)
                        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                 class="d-block w-100"
                                 style="height:400px; object-fit:cover;"
                                 alt="Foto {{ $room->name }} {{ $i + 1 }}">
                        </div>
                        @endforeach
                    @else
                        <div class="carousel-item active">
                            <div style="height:400px; background:#1a2d40;
                                        display:flex; align-items:center; justify-content:center;">
                                <div class="text-center">
                                    <i class="bi bi-image" style="font-size:4rem; color:rgba(255,255,255,0.1);"></i>
                                    <p style="color:var(--text-muted); margin-top:8px; font-size:.85rem;">
                                        Belum ada foto
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Controls (hanya tampil jika foto > 1) --}}
                @if($room->photos->count() > 1)
                <button class="carousel-control-prev" type="button"
                        data-bs-target="#roomCarousel" data-bs-slide="prev">
                    <i class="bi bi-chevron-left" style="font-size:1.1rem;"></i>
                </button>
                <button class="carousel-control-next" type="button"
                        data-bs-target="#roomCarousel" data-bs-slide="next">
                    <i class="bi bi-chevron-right" style="font-size:1.1rem;"></i>
                </button>
                @endif

            </div>

            {{-- Thumbnail strip --}}
            @if($room->photos->count() > 1)
            <div class="d-flex gap-2 mt-3 flex-wrap">
                @foreach($room->photos as $i => $photo)
                <img src="{{ asset('storage/' . $photo->photo_path) }}"
                     class="thumb-item {{ $i === 0 ? 'active' : '' }}"
                     alt="Thumbnail {{ $i + 1 }}"
                     onclick="goToSlide({{ $i }}, this)">
                @endforeach
            </div>
            @endif

        </div>

        {{-- ── KOLOM KANAN: Info ── --}}
        <div class="col-lg-5">
            <div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.07);
                        border-radius:18px; padding:1.75rem; height:100%;">

                {{-- Nama & Badge --}}
                <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
                    <h3 style="color:var(--text-white); font-weight:700; margin:0;">
                        {{ $room->name }}
                    </h3>
                    @php
                        $badgeMap = [
                            'available'   => ['label' => 'Tersedia',    'bg' => 'rgba(34,197,94,0.15)',  'color' => '#4ade80'],
                            'occupied'    => ['label' => 'Terisi',      'bg' => 'rgba(239,68,68,0.15)',  'color' => '#f87171'],
                            'maintenance' => ['label' => 'Maintenance', 'bg' => 'rgba(234,179,8,0.15)',  'color' => '#fbbf24'],
                        ];
                        $badge = $badgeMap[$room->status] ?? ['label' => ucfirst($room->status), 'bg' => 'rgba(255,255,255,0.1)', 'color' => '#fff'];
                    @endphp
                    <span style="background:{{ $badge['bg'] }}; color:{{ $badge['color'] }};
                                 font-size:.75rem; padding:5px 12px; border-radius:999px;
                                 font-weight:600; white-space:nowrap;">
                        {{ $badge['label'] }}
                    </span>
                </div>

                {{-- Harga --}}
                <div style="margin-bottom:1.25rem;">
                    <span style="color:var(--accent); font-size:1.8rem; font-weight:700;">
                        Rp {{ number_format($room->price, 0, ',', '.') }}
                    </span>
                    <span style="color:var(--text-muted); font-size:.9rem;">/bulan</span>
                </div>

                <hr style="border-color:rgba(255,255,255,0.08); margin-bottom:1.25rem;">

                {{-- Spesifikasi --}}
                <div class="d-flex flex-column gap-2 mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:36px; height:36px; background:rgba(255,140,50,0.1);
                                    border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-tag-fill" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <div style="font-size:.75rem; color:var(--text-muted);">Tipe</div>
                            <div style="color:var(--text-white); font-weight:500;">{{ $room->type }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:36px; height:36px; background:rgba(255,140,50,0.1);
                                    border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-aspect-ratio-fill" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <div style="font-size:.75rem; color:var(--text-muted);">Ukuran</div>
                            <div style="color:var(--text-white); font-weight:500;">{{ $room->size }} m²</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:36px; height:36px; background:rgba(255,140,50,0.1);
                                    border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-building-fill" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <div style="font-size:.75rem; color:var(--text-muted);">Lantai</div>
                            <div style="color:var(--text-white); font-weight:500;">Lantai {{ $room->floor }}</div>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                @if($room->description)
                <p style="color:var(--text-muted); font-size:.9rem; line-height:1.7;
                           margin-bottom:1.25rem;">
                    {{ $room->description }}
                </p>
                @endif

                {{-- Fasilitas --}}
                @if($room->facilities && count($room->facilities) > 0)
                <div style="margin-bottom:1.5rem;">
                    <h6 style="color:var(--text-white); font-weight:600; margin-bottom:10px;">
                        <i class="bi bi-lightning-fill me-1" style="color:var(--accent);"></i>
                        Fasilitas
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($room->facilities as $facility)
                        <span style="font-size:.78rem; padding:5px 11px;
                                     background:rgba(255,255,255,0.05);
                                     border:1px solid rgba(255,255,255,0.09);
                                     border-radius:999px; color:var(--text-muted);">
                            <i class="bi bi-check2 me-1" style="color:var(--accent);"></i>{{ $facility }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Alert tersedia --}}
                @if($room->status === 'available')
                <div style="background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.2);
                            border-radius:10px; padding:12px 16px; margin-bottom:1.25rem;">
                    <p style="color:#4ade80; margin:0; font-size:.88rem;">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Kamar ini <strong>tersedia</strong> untuk disewa. Hubungi pengelola untuk informasi lebih lanjut.
                    </p>
                </div>
                @endif

                {{-- Tombol Hubungi --}}
                <a href="https://wa.me/628123456789"
                   target="_blank"
                   style="display:block; text-align:center; padding:13px;
                          background:var(--accent); color:#fff; text-decoration:none;
                          border-radius:12px; font-weight:600; font-size:.95rem;
                          transition:opacity .2s;"
                   onmouseover="this.style.opacity='.85'"
                   onmouseout="this.style.opacity='1'">
                    <i class="bi bi-whatsapp me-2"></i>
                    Hubungi via WhatsApp
                </a>

            </div>
        </div>

    </div>

    {{-- ── KAMAR TERKAIT ── --}}
    @if($relatedRooms->count())
    <div class="mt-5">
        <h5 style="color:var(--text-white); font-weight:700; margin-bottom:1.25rem;">
            <i class="bi bi-grid me-2" style="color:var(--accent);"></i>
            Kamar Serupa Tersedia
        </h5>
        <div class="row g-3">
            @foreach($relatedRooms as $related)
            <div class="col-md-4">
                <div class="related-card">
                    @if($related->primaryPhoto)
                        <img src="{{ asset('storage/' . $related->primaryPhoto->photo_path) }}"
                             style="width:100%; height:160px; object-fit:cover; display:block;"
                             alt="{{ $related->name }}">
                    @else
                        <div style="height:160px; background:#1a2d40;
                                    display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-image" style="font-size:2rem; color:rgba(255,255,255,0.1);"></i>
                        </div>
                    @endif
                    <div style="padding:1rem;">
                        <h6 style="color:var(--text-white); font-weight:600; margin-bottom:4px;">
                            {{ $related->name }}
                        </h6>
                        <p style="color:var(--accent); font-weight:700; margin-bottom:10px; font-size:.95rem;">
                            Rp {{ number_format($related->price, 0, ',', '.') }}
                            <span style="color:var(--text-muted); font-weight:400; font-size:.8rem;">/bln</span>
                        </p>
                        <a href="{{ route('rooms.show', $related) }}"
                           style="display:block; text-align:center; padding:8px;
                                  border:1px solid rgba(255,140,50,0.35); color:var(--accent);
                                  border-radius:8px; font-size:.85rem; text-decoration:none;
                                  transition:background .2s;"
                           onmouseover="this.style.background='rgba(255,140,50,0.1)'"
                           onmouseout="this.style.background='transparent'">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    // Sync thumbnail dengan carousel
    function goToSlide(index, el) {
        const carousel = bootstrap.Carousel.getOrCreateInstance(
            document.getElementById('roomCarousel')
        );
        carousel.to(index);

        document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    // Update thumbnail saat carousel bergerak
    document.getElementById('roomCarousel')?.addEventListener('slid.bs.carousel', function (e) {
        document.querySelectorAll('.thumb-item').forEach((t, i) => {
            t.classList.toggle('active', i === e.to);
        });
    });
</script>
@endpush