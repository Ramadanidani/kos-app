<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — ManageMyKos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bg-main: #243447; --bg-card: #2E4159;
            --accent: #FF8C32; --text-white: #F5F7FA; --text-muted: #B8C2CC;
        }
        body {
            background: var(--bg-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .role-card {
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: all .25s;
            position: relative;
            overflow: hidden;
        }
        .role-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0,0,0,.35);
            border-color: rgba(255,255,255,0.18);
        }
        .role-card .icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            font-size: 1.8rem;
            transition: transform .25s;
        }
        .role-card:hover .icon-wrap {
            transform: scale(1.1);
        }
        .role-card h5 {
            color: var(--text-white);
            font-weight: 700;
            margin-bottom: 6px;
            font-size: 1.1rem;
        }
        .role-card p {
            color: var(--text-muted);
            font-size: .82rem;
            margin: 0;
            line-height: 1.6;
        }
        .role-card .arrow {
            margin-top: 1.25rem;
            font-size: .82rem;
            opacity: 0;
            transform: translateX(-6px);
            transition: all .25s;
        }
        .role-card:hover .arrow {
            opacity: 1;
            transform: translateX(0);
        }
    </style>
</head>
<body>

<div style="width:100%; max-width:520px; padding:1.5rem;">

    {{-- Logo --}}
    <div style="text-align:center; margin-bottom:2.5rem;">
        <div style="width:60px; height:60px; background:rgba(255,140,50,0.15);
                    border-radius:16px; display:flex; align-items:center;
                    justify-content:center; margin:0 auto 14px;">
            <i class="bi bi-building" style="font-size:1.8rem; color:var(--accent);"></i>
        </div>
        <h3 style="color:var(--text-white); font-weight:700; margin-bottom:6px;">
            Masuk ke ManageMyKos
        </h3>
        <p style="color:var(--text-muted); font-size:.9rem; margin:0;">
            Pilih akun untuk melanjutkan
        </p>
    </div>

    {{-- Pilihan Role --}}
    <div class="row g-3">

        {{-- Penghuni --}}
        <div class="col-6">
            <a href="{{ route('tenant.login') }}" class="role-card">
                <div class="icon-wrap"
                     style="background:rgba(255,140,50,0.12); border:1px solid rgba(255,140,50,0.2);">
                    <i class="bi bi-person-fill" style="color:var(--accent);"></i>
                </div>
                <h5>Penghuni</h5>
                <p>Login sebagai penghuni kos untuk melihat tagihan dan informasi kamar</p>
                <div class="arrow" style="color:var(--accent);">
                    Masuk sebagai Penghuni →
                </div>
            </a>
        </div>

        {{-- Admin --}}
        <div class="col-6">
            <a href="{{ route('login') }}" class="role-card">
                <div class="icon-wrap"
                     style="background:rgba(96,165,250,0.12); border:1px solid rgba(96,165,250,0.2);">
                    <i class="bi bi-shield-fill-check" style="color:#60a5fa;"></i>
                </div>
                <h5>Admin</h5>
                <p>Login sebagai admin atau pemilik kos untuk mengelola properti</p>
                <div class="arrow" style="color:#60a5fa;">
                    Masuk sebagai Admin →
                </div>
            </a>
        </div>

    </div>

    {{-- Back --}}
    <div style="text-align:center; margin-top:1.75rem;">
        <a href="{{ url('/') }}"
           style="color:var(--text-muted); font-size:.85rem; text-decoration:none;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
        </a>
    </div>

</div>

</body>
</html>