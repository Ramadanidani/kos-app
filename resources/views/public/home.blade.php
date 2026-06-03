@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

{{-- ── HERO ── --}}
<section style="position:relative; padding:75px 2rem 70px; overflow:hidden;">

    {{-- Glow background --}}
    <div style="position:absolute; width:500px; height:500px;
                background:rgba(255,140,50,0.08); filter:blur(120px);
                top:-120px; right:-120px; z-index:0; pointer-events:none;"></div>

    <div class="container" style="position:relative; z-index:2;">
        <div class="row align-items-center g-5">

            {{-- KIRI: Teks --}}
            <div class="col-lg-5">

                {{-- Badge --}}
                <div style="display:inline-flex; align-items:center; gap:8px;
                            padding:8px 14px; background:rgba(255,140,50,0.12);
                            border:1px solid rgba(255,140,50,0.2); border-radius:999px;
                            color:var(--accent); font-size:.85rem; margin-bottom:22px;">
                    <i class="bi bi-stars"></i>
                    Platform Manajemen Kos Modern
                </div>

                {{-- Heading --}}
                <h1 style="font-size:clamp(2.2rem,5vw,3.6rem); font-weight:800;
                            line-height:1.15; color:var(--text-white); margin-bottom:22px;">
                    Temukan
                    <span style="color:var(--accent);">Kamar Kos</span>
                    Nyaman Untuk Hidup Lebih Produktif
                </h1>

                {{-- Deskripsi --}}
                <p style="color:var(--text-muted); line-height:1.9; font-size:1rem;
                            max-width:480px; margin-bottom:32px;">
                    Cari hunian terbaik dengan fasilitas lengkap, lokasi strategis,
                    dan lingkungan nyaman untuk mahasiswa maupun pekerja.
                </p>

                {{-- CTA Buttons --}}
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('rooms.index') }}"
                       style="background:var(--accent); color:#fff; text-decoration:none;
                              padding:14px 28px; border-radius:12px; font-weight:600;
                              display:inline-flex; align-items:center; gap:10px;
                              box-shadow:0 10px 30px rgba(255,140,50,.25); transition:.25s;"
                       onmouseover="this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.transform='translateY(0)'">
                        <i class="bi bi-door-open-fill"></i>
                        Jelajahi Kamar
                    </a>
                    <a href="#fasilitas"
                       style="border:1px solid rgba(255,255,255,0.1); color:var(--text-white);
                              text-decoration:none; padding:14px 24px; border-radius:12px;
                              font-weight:500; background:rgba(255,255,255,0.03);
                              transition:.25s;"
                       onmouseover="this.style.borderColor='rgba(255,255,255,0.25)'"
                       onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
                        Lihat Fasilitas
                    </a>
                </div>

                {{-- Stats dari database --}}
                <div class="d-flex gap-5 mt-5 flex-wrap">
                    <div>
                        <h3 style="color:var(--text-white); font-weight:700; margin:0;">
                            {{ $totalRooms }}+
                        </h3>
                        <p style="color:var(--text-muted); margin:0; font-size:.9rem;">Total Kamar</p>
                    </div>
                    <div>
                        <h3 style="color:var(--text-white); font-weight:700; margin:0;">
                            {{ $availableRooms }}
                        </h3>
                        <p style="color:var(--text-muted); margin:0; font-size:.9rem;">Kamar Tersedia</p>
                    </div>
                    <div>
                        <h3 style="color:var(--text-white); font-weight:700; margin:0;">24/7</h3>
                        <p style="color:var(--text-muted); margin:0; font-size:.9rem;">Keamanan</p>
                    </div>
                </div>

            </div>

            {{-- KANAN: Gambar --}}
            <div class="col-lg-7">
                <div style="position:relative; border:1px solid rgba(255,255,255,0.08);
                            border-radius:28px; overflow:hidden; min-height:460px;
                            box-shadow:0 20px 60px rgba(0,0,0,.35);">

                    {{-- Foto hero --}}
                    <div style="position:absolute; inset:0;
                                background: linear-gradient(to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)),
                                url('https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?q=80&w=1400&auto=format&fit=crop');
                                background-size:cover; background-position:center;">
                    </div>

                    {{-- Floating card --}}
                    <div style="position:absolute; bottom:24px; left:24px; right:24px;
                                background:rgba(18,18,18,.75); backdrop-filter:blur(12px);
                                border:1px solid rgba(255,255,255,.08);
                                border-radius:18px; padding:18px 20px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 style="color:var(--text-white); margin:0 0 6px; font-weight:600;">
                                    Kamar Deluxe Modern
                                </h5>
                                <p style="color:var(--text-muted); margin:0; font-size:.88rem;">
                                    <i class="bi bi-wifi me-1"></i>WiFi
                                    &nbsp;·&nbsp;
                                    <i class="bi bi-snow me-1"></i>AC
                                    &nbsp;·&nbsp;
                                    <i class="bi bi-door-closed me-1"></i>Kamar Mandi Dalam
                                </p>
                            </div>
                            <div style="color:var(--accent); font-weight:700; font-size:1.1rem;
                                        white-space:nowrap;">
                                Rp 1.2jt
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- ── TENTANG KAMI ── --}}
<section id="tentang" style="padding:70px 2rem;">
    <div class="container">
        <div class="row align-items-center g-5">

            {{-- KIRI: Gambar --}}
            <div class="col-lg-6">
                <div style="position:relative;">

                    {{-- Gambar utama --}}
                    <div style="border-radius:20px; overflow:hidden;
                                border:1px solid rgba(255,255,255,0.08);
                                box-shadow:0 20px 50px rgba(0,0,0,.3);">
                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=1200&auto=format&fit=crop"
                             style="width:100%; height:380px; object-fit:cover; display:block;"
                             alt="Tentang ManageMyKos">
                    </div>

                    {{-- Floating stats card --}}
                    <div style="position:absolute; bottom:-20px; right:-16px;
                                background:var(--bg-card);
                                border:1px solid rgba(255,255,255,0.08);
                                border-radius:16px; padding:16px 20px;
                                box-shadow:0 8px 24px rgba(0,0,0,.3);
                                min-width:160px;">
                        <div style="font-size:1.6rem; font-weight:700; color:var(--accent);">
                            {{ $totalRooms }}+
                        </div>
                        <div style="color:var(--text-muted); font-size:.82rem;">
                            Kamar Dikelola
                        </div>
                        <hr style="border-color:rgba(255,255,255,0.08); margin:10px 0;">
                        <div style="font-size:1.6rem; font-weight:700; color:#4ade80;">
                            {{ $availableRooms }}
                        </div>
                        <div style="color:var(--text-muted); font-size:.82rem;">
                            Kamar Tersedia
                        </div>
                    </div>

                </div>
            </div>

            {{-- KANAN: Konten --}}
            <div class="col-lg-6">

                {{-- Label --}}
                <div style="display:inline-flex; align-items:center; gap:8px;
                            padding:7px 14px; background:rgba(255,140,50,0.12);
                            border:1px solid rgba(255,140,50,0.2); border-radius:999px;
                            color:var(--accent); font-size:.82rem; margin-bottom:18px;">
                    <i class="bi bi-info-circle-fill"></i>
                    Tentang Kami
                </div>

                <h2 style="color:var(--text-white); font-weight:700;
                            font-size:clamp(1.6rem,3vw,2.2rem); line-height:1.25;
                            margin-bottom:16px;">
                    Solusi Manajemen Kos
                    <span style="color:var(--accent);">Modern & Terpercaya</span>
                </h2>

                <p style="color:var(--text-muted); line-height:1.85; font-size:.95rem;
                           margin-bottom:20px;">
                    ManageMyKos hadir sebagai platform digital yang memudahkan pemilik kos
                    dalam mengelola properti dan memudahkan calon penghuni menemukan
                    hunian terbaik sesuai kebutuhan.
                </p>

                <p style="color:var(--text-muted); line-height:1.85; font-size:.95rem;
                           margin-bottom:28px;">
                    Dengan sistem yang terintegrasi — mulai dari manajemen kamar,
                    pembayaran, hingga notifikasi otomatis — kami berkomitmen untuk
                    memberikan pengalaman terbaik bagi semua pihak.
                </p>

                {{-- Keunggulan list --}}
                <div class="d-flex flex-column gap-3">

                    <div class="d-flex align-items-start gap-3">
                        <div style="width:36px; height:36px; background:rgba(255,140,50,0.12);
                                    border-radius:9px; display:flex; align-items:center;
                                    justify-content:center; flex-shrink:0;
                                    border:1px solid rgba(255,140,50,0.15);">
                            <i class="bi bi-phone-fill" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <h6 style="color:var(--text-white); font-weight:600; margin-bottom:3px;">
                                Sistem Digital Terintegrasi
                            </h6>
                            <p style="color:var(--text-muted); font-size:.85rem; margin:0; line-height:1.6;">
                                Kelola kamar, penghuni, dan pembayaran dalam satu platform.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div style="width:36px; height:36px; background:rgba(255,140,50,0.12);
                                    border-radius:9px; display:flex; align-items:center;
                                    justify-content:center; flex-shrink:0;
                                    border:1px solid rgba(255,140,50,0.15);">
                            <i class="bi bi-bell-fill" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <h6 style="color:var(--text-white); font-weight:600; margin-bottom:3px;">
                                Notifikasi WhatsApp Otomatis
                            </h6>
                            <p style="color:var(--text-muted); font-size:.85rem; margin:0; line-height:1.6;">
                                Reminder pembayaran dan info penting langsung ke WhatsApp penghuni.
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div style="width:36px; height:36px; background:rgba(255,140,50,0.12);
                                    border-radius:9px; display:flex; align-items:center;
                                    justify-content:center; flex-shrink:0;
                                    border:1px solid rgba(255,140,50,0.15);">
                            <i class="bi bi-headset" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <h6 style="color:var(--text-white); font-weight:600; margin-bottom:3px;">
                                Laporan Keluhan Real-time
                            </h6>
                            <p style="color:var(--text-muted); font-size:.85rem; margin:0; line-height:1.6;">
                                Penghuni bisa buat laporan keluhan dan tracking statusnya langsung.
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>

{{-- ── FEATURES ── --}}
<section id="fasilitas"
         style="background:var(--bg-card);
                border-top:1px solid rgba(255,255,255,0.07);
                border-bottom:1px solid rgba(255,255,255,0.07);
                padding:50px 2rem;">
    <div class="container">

        {{-- Section title --}}
        <div class="text-center mb-5">
            <h2 style="color:var(--text-white); font-weight:700; margin-bottom:8px;">
                Kenapa Pilih <span style="color:var(--accent);">ManageMyKos?</span>
            </h2>
            <p style="color:var(--text-muted); margin:0; font-size:.95rem;">
                Kami hadir untuk memudahkan pengalaman tinggal di kos.
            </p>
        </div>

        <div class="row g-4 text-center">

            <div class="col-6 col-md-3">
                <div style="background:rgba(255,140,50,0.1); width:56px; height:56px;
                            border-radius:14px; display:flex; align-items:center;
                            justify-content:center; margin:0 auto 14px;
                            border:1px solid rgba(255,140,50,0.15);">
                    <i class="bi bi-geo-alt-fill" style="color:var(--accent); font-size:1.5rem;"></i>
                </div>
                <h6 style="color:var(--text-white); font-weight:600; margin-bottom:6px;">Lokasi Strategis</h6>
                <p style="color:var(--text-muted); font-size:.82rem; margin:0; line-height:1.6;">
                    Dekat pusat kota, kampus, dan transportasi umum.
                </p>
            </div>

            <div class="col-6 col-md-3">
                <div style="background:rgba(255,140,50,0.1); width:56px; height:56px;
                            border-radius:14px; display:flex; align-items:center;
                            justify-content:center; margin:0 auto 14px;
                            border:1px solid rgba(255,140,50,0.15);">
                    <i class="bi bi-tools" style="color:var(--accent); font-size:1.5rem;"></i>
                </div>
                <h6 style="color:var(--text-white); font-weight:600; margin-bottom:6px;">Fasilitas Lengkap</h6>
                <p style="color:var(--text-muted); font-size:.82rem; margin:0; line-height:1.6;">
                    WiFi, AC, laundry, dan berbagai fasilitas modern.
                </p>
            </div>

            <div class="col-6 col-md-3">
                <div style="background:rgba(255,140,50,0.1); width:56px; height:56px;
                            border-radius:14px; display:flex; align-items:center;
                            justify-content:center; margin:0 auto 14px;
                            border:1px solid rgba(255,140,50,0.15);">
                    <i class="bi bi-shield-check" style="color:var(--accent); font-size:1.5rem;"></i>
                </div>
                <h6 style="color:var(--text-white); font-weight:600; margin-bottom:6px;">Keamanan 24 Jam</h6>
                <p style="color:var(--text-muted); font-size:.82rem; margin:0; line-height:1.6;">
                    CCTV aktif dan petugas keamanan siap siaga.
                </p>
            </div>

            <div class="col-6 col-md-3">
                <div style="background:rgba(255,140,50,0.1); width:56px; height:56px;
                            border-radius:14px; display:flex; align-items:center;
                            justify-content:center; margin:0 auto 14px;
                            border:1px solid rgba(255,140,50,0.15);">
                    <i class="bi bi-house-heart-fill" style="color:var(--accent); font-size:1.5rem;"></i>
                </div>
                <h6 style="color:var(--text-white); font-weight:600; margin-bottom:6px;">Lingkungan Nyaman</h6>
                <p style="color:var(--text-muted); font-size:.82rem; margin:0; line-height:1.6;">
                    Suasana bersih, tenang, dan kondusif untuk beristirahat.
                </p>
            </div>

        </div>
    </div>
</section>

@endsection