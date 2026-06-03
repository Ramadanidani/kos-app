<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — ManageMyKos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bg-main:    #243447;
            --bg-card:    #2E4159;
            --accent:     #FF8C32;
            --text-white: #F5F7FA;
            --text-muted: #B8C2CC;
            --sidebar-w:  240px;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bg-main);
            color: var(--text-white);
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--bg-card);
            border-right: 1px solid rgba(255,255,255,0.07);
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 1.4rem 1.2rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .sidebar-logo i { color: var(--accent); font-size: 1.4rem; }
        .sidebar-logo span { color: var(--text-white); font-weight: 700; font-size: 1rem; }
        .sidebar-logo .sep { color: rgba(255,255,255,0.3); }

        .sidebar-menu {
            padding: 1rem 0.75rem;
            flex-grow: 1;
        }

        .menu-label {
            font-size: .68rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: 0.5rem 0.6rem 0.3rem;
            margin-top: 0.5rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: .88rem;
            transition: all .2s;
            margin-bottom: 2px;
        }
        .menu-item i { font-size: 1rem; width: 18px; text-align: center; }
        .menu-item:hover {
            background: rgba(255,255,255,0.06);
            color: var(--text-white);
        }
        .menu-item.active {
            background: rgba(255,140,50,0.15);
            color: var(--accent);
            border: 1px solid rgba(255,140,50,0.2);
        }
        .menu-item.active i { color: var(--accent); }

        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--bg-card);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            padding: 0 1.5rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .topbar-title {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-white);
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .admin-avatar {
            width: 34px;
            height: 34px;
            background: rgba(255,140,50,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: .9rem;
        }

        /* ── PAGE BODY ── */
        .page-body {
            padding: 1.75rem;
            flex-grow: 1;
        }

        /* ── STAT CARD ── */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px;
            padding: 1.25rem 1.4rem;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,.25);
        }
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: .85rem;
        }

        /* ── CONTENT CARD ── */
        .content-card {
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px;
            overflow: hidden;
        }
        .content-card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content-card-body { padding: 1rem 1.25rem; }

        /* ── LIST ITEM ── */
        .list-item-mk {
            padding: .85rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }
        .list-item-mk:last-child { border-bottom: none; }

        /* ── BADGE STATUS ── */
        .badge-pending     { background:rgba(234,179,8,0.15);  color:#fbbf24; }
        .badge-in_progress { background:rgba(59,130,246,0.15); color:#60a5fa; }
        .badge-resolved    { background:rgba(34,197,94,0.15);  color:#4ade80; }
        .badge-unpaid      { background:rgba(239,68,68,0.15);  color:#f87171; }
        .badge-paid        { background:rgba(34,197,94,0.15);  color:#4ade80; }
        .badge-overdue     { background:rgba(239,68,68,0.2);   color:#f87171; }

        /* ── LINK SEE ALL ── */
        .link-accent {
            color: var(--accent);
            font-size: .82rem;
            text-decoration: none;
        }
        .link-accent:hover { opacity: .8; color: var(--accent); }

        /* ── TABLE ── */
        .table-mk { width: 100%; border-collapse: collapse; }
        .table-mk th {
            font-size: .75rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: .6rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            font-weight: 500;
        }
        .table-mk td {
            padding: .85rem 1rem;
            font-size: .88rem;
            color: var(--text-white);
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .table-mk tr:last-child td { border-bottom: none; }
        .table-mk tr:hover td { background: rgba(255,255,255,0.02); }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <i class="bi bi-building"></i>
        <span>Logo <span class="sep">|</span> ManageMyKos</span>
    </a>

    <nav class="sidebar-menu">
        <div class="menu-label">Menu Utama</div>

        <a href="{{ route('admin.dashboard') }}"
           class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="{{ route('admin.rooms.index') }}"
           class="menu-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
            <i class="bi bi-door-open"></i> Daftar Kamar
        </a>

        <a href="{{ route('admin.tenants.index') }}"
           class="menu-item {{ request()->routeIs('admin.tenants.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Penghuni
        </a>

        <a href="{{ route('admin.payments.index') }}"
            class="menu-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Pembayaran
        </a>
        <a href="{{ route('admin.payment-info.edit') }}"
            class="menu-item {{ request()->routeIs('admin.payment-info.*') ? 'active' : '' }}">
            <i class="bi bi-qr-code"></i> Info Pembayaran
        </a>
        <a href="{{ route('admin.payment-reports.index') }}"
            class="menu-item {{ request()->routeIs('admin.payment-reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-check"></i> Laporan Bayar
            @if(isset($pendingReports) && $pendingReports > 0)
                <span class="menu-badge">{{ $pendingReports }}</span>
            @endif
        </a>

        <div class="menu-label">Manajemen</div>

        <a href="{{ route('admin.complaints.index') }}"
            class="menu-item {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
            <i class="bi bi-chat-square-text"></i> Keluhan
        </a>

        <a href="{{ route('admin.transfers.index') }}"
            class="menu-item {{ request()->routeIs('admin.transfers.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i> Pengajuan Pindah
        </a>
        
        <div class="menu-label">Notifikasi</div>

        <a href="{{ route('admin.whatsapp.index') }}"
        class="menu-item {{ request()->routeIs('admin.whatsapp.*') ? 'active' : '' }}">
            <i class="bi bi-whatsapp"></i> WA Reminder
        </a>

    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="width:100%; background:rgba(239,68,68,0.1); color:#f87171;
                           border:1px solid rgba(239,68,68,0.2); border-radius:10px;
                           padding:9px 14px; font-size:.88rem; cursor:pointer;
                           display:flex; align-items:center; gap:10px; transition:.2s;"
                    onmouseover="this.style.background='rgba(239,68,68,0.2)'"
                    onmouseout="this.style.background='rgba(239,68,68,0.1)'">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </div>
</aside>

{{-- ── MAIN ── --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        <div class="topbar-right">
            <span style="font-size:.85rem; color:var(--text-muted);">
                {{ Auth::user()->name }}
            </span>
            <div class="admin-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div style="margin:1rem 1.75rem 0; background:rgba(34,197,94,0.12);
                border:1px solid rgba(34,197,94,0.2); border-radius:10px;
                padding:12px 16px; color:#4ade80; font-size:.88rem;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="margin:1rem 1.75rem 0; background:rgba(239,68,68,0.12);
                border:1px solid rgba(239,68,68,0.2); border-radius:10px;
                padding:12px 16px; color:#f87171; font-size:.88rem;">
        <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
    </div>
    @endif

    {{-- Page Content --}}
    <div class="page-body">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>