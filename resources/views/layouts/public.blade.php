<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ManageMyKos') — Kos Nyaman & Terjangkau</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bg-main:    #243447;
            --bg-card:    #2E4159;
            --accent:     #FF8C32;
            --text-white: #F5F7FA;
            --text-muted: #B8C2CC;
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--bg-main);
            color: var(--text-white);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
        }

        /* ── NAVBAR ── */
        .navbar-mk {
            background: var(--bg-card);
            padding: 0 2rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand-mk {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-white) !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .navbar-brand-mk .sep { color: rgba(255,255,255,0.3); margin: 0 4px; }
        .navbar-brand-mk i { color: var(--accent); font-size: 1.3rem; }

        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            padding: 6px 12px;
            border-radius: 6px;
            transition: color .2s;
        }
        .nav-links a:hover,
        .nav-links a.active { color: var(--text-white); }

        .btn-login {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 7px 18px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: opacity .2s;
        }
        .btn-login:hover { opacity: .88; color: #fff; }

        .btn-contact {
            background: transparent;
            color: var(--text-white);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 7px;
            padding: 6px 14px;
            font-size: 0.875rem;
            text-decoration: none;
            transition: border-color .2s;
        }
        .btn-contact:hover { border-color: rgba(255,255,255,0.5); color: var(--text-white); }

        /* ── FOOTER ── */
        .footer-mk {
            background: var(--bg-card);
            border-top: 1px solid rgba(255,255,255,0.07);
            text-align: center;
            padding: 1.2rem;
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: auto;
        }

        /* ── BADGE STATUS ── */
        .badge-available   { background: rgba(34,197,94,0.15);  color: #4ade80; }
        .badge-occupied    { background: rgba(239,68,68,0.15);  color: #f87171; }
        .badge-maintenance { background: rgba(234,179,8,0.15);  color: #fbbf24; }

        /* ── CARD KAMAR ── */
        .card-mk {
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            overflow: hidden;
            transition: transform .25s, box-shadow .25s;
        }
        .card-mk:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 28px rgba(0,0,0,.35); 
        }

        /* ── UTILITIES ── */
        .text-accent  { color: var(--accent); }
        .text-mk-muted { color: var(--text-muted); }
        .bg-card-mk   { background: var(--bg-card); }

        /* Smooth scroll untuk anchor */
        html { scroll-behavior: smooth; }

        /* Offset supaya tidak ketutup navbar sticky */
        #tentang, #fasilitas {
            scroll-margin-top: 70px;
        }
    </style>

    @stack('styles')
</head>
<body class="d-flex flex-column">

{{-- NAVBAR --}}
<nav class="navbar-mk">
    <div class="d-flex align-items-center gap-2">
    <img src="{{ asset('images/logokos.png') }}"
         alt="ManageMyKos"
         style="height: 62px; width: auto;">

        <span class="text-secondary fs-4">|</span>

        <span class="fw-bold fs-4">
            ManageMyKos
        </span>
    </div>

    <div class="nav-links">
        <a href="{{ url('/') }}"        class="{{ request()->is('/')       ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('rooms.index') }}" class="{{ request()->is('kamar*') ? 'active' : '' }}">Kamar</a>
        <a href="{{ url('/') }}#fasilitas">Fasilitas</a>
        <a href="{{ url('/') }}#tentang" 
        class="{{ request()->is('/') ? '' : '' }}">Tentang Kami</a>
        <a href="https://wa.me/6281388036130" 
        target="_blank"
        class="btn-contact">
            Kontak Kami
        </a>
        <a href="{{ route('select-role') }}" class="btn-login ms-2">Login</a>
    </div>
</nav>

{{-- KONTEN HALAMAN --}}
@yield('content')

{{-- FOOTER --}}
<footer class="footer-mk">
    &copy; {{ date('Y') }} ManageMyKos. Semua hak dilindungi.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>