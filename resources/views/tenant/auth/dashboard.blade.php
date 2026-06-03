<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penghuni — ManageMyKos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bg-main:#243447; --bg-card:#2E4159; --accent:#FF8C32; --text-white:#F5F7FA; --text-muted:#B8C2CC; }
        body { background:var(--bg-main); color:var(--text-white); font-family:'Segoe UI',sans-serif; min-height:100vh; }
        .topbar { background:var(--bg-card); padding:0 1.5rem; height:60px; display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid rgba(255,255,255,0.07); position:sticky; top:0; z-index:100; }
        .card-mk { background:var(--bg-card); border:1px solid rgba(255,255,255,0.07); border-radius:14px; padding:1.25rem; }
    </style>
</head>
<body>

{{-- Topbar --}}
<div class="topbar">
    <div style="display:flex; align-items:center; gap:10px;">
        <i class="bi bi-building" style="color:var(--accent); font-size:1.3rem;"></i>
        <span style="font-weight:700; font-size:1rem;">ManageMyKos</span>
    </div>
    <div style="display:flex; align-items:center; gap:14px;">
        <span style="font-size:.85rem; color:var(--text-muted);">
            {{ Auth::guard('tenant')->user()->name }}
        </span>
        <form method="POST" action="{{ route('tenant.logout') }}">
            @csrf
            <button type="submit"
                    style="background:rgba(239,68,68,0.1); color:#f87171;
                           border:1px solid rgba(239,68,68,0.2); border-radius:8px;
                           padding:6px 14px; font-size:.82rem; cursor:pointer;">
                <i class="bi bi-box-arrow-left me-1"></i> Logout
            </button>
        </form>
    </div>
</div>

<div class="container py-4">

    @if(session('success'))
    <div style="background:rgba(34,197,94,0.12); border:1px solid rgba(34,197,94,0.2);
                border-radius:10px; padding:12px 16px; margin-bottom:1.25rem;
                color:#4ade80; font-size:.88rem;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    </div>
    @endif

    {{-- Header --}}
    <div class="mb-4">
        <h4 style="font-weight:700; color:var(--text-white); margin-bottom:4px;">
            Halo, {{ Auth::guard('tenant')->user()->name }}! 👋
        </h4>
        <p style="color:var(--text-muted); margin:0; font-size:.9rem;">
            Selamat datang di portal penghuni ManageMyKos.
        </p>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card-mk text-center">
                <i class="bi bi-door-open-fill"
                   style="font-size:1.8rem; color:var(--accent); margin-bottom:8px; display:block;"></i>
                <div style="font-size:1.2rem; font-weight:700; color:var(--text-white);">
                    {{ $tenant->room->name ?? '—' }}
                </div>
                <div style="color:var(--text-muted); font-size:.8rem;">Kamar Saya</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-mk text-center">
                <i class="bi bi-credit-card-fill"
                   style="font-size:1.8rem; color:{{ $unpaidCount > 0 ? '#f87171' : '#4ade80' }};
                          margin-bottom:8px; display:block;"></i>
                <div style="font-size:1.2rem; font-weight:700;
                            color:{{ $unpaidCount > 0 ? '#f87171' : '#4ade80' }};">
                    {{ $unpaidCount }}
                </div>
                <div style="color:var(--text-muted); font-size:.8rem;">Tagihan Belum Bayar</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-mk text-center">
                <i class="bi bi-calendar-check-fill"
                   style="font-size:1.8rem; color:#60a5fa; margin-bottom:8px; display:block;"></i>
                <div style="font-size:1.2rem; font-weight:700; color:var(--text-white);">
                    {{ $tenant->start_date?->format('M Y') ?? '—' }}
                </div>
                <div style="color:var(--text-muted); font-size:.8rem;">Mulai Sewa</div>
            </div>
        </div>
    </div>

    {{-- Tagihan & Keluhan --}}
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card-mk">
                <h6 style="color:var(--text-white); font-weight:600; margin-bottom:1rem;">
                    <i class="bi bi-credit-card me-2" style="color:var(--accent);"></i>
                    Tagihan Terbaru
                </h6>
                @forelse($tenant->payments as $payment)
                <div style="display:flex; justify-content:space-between; align-items:center;
                            padding:.6rem 0; border-bottom:1px solid rgba(255,255,255,0.05);">
                    <div>
                        <div style="color:var(--text-white); font-size:.88rem; font-weight:500;">
                            {{ $payment->due_date->format('M Y') }}
                        </div>
                        <div style="color:var(--text-muted); font-size:.75rem;">
                            {{ $payment->method ?? 'Belum ada metode' }}
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="color:var(--accent); font-weight:600; font-size:.88rem;">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </div>
                        @php
                            $s = ['paid'=>['Lunas','#4ade80'],'unpaid'=>['Belum Bayar','#f87171'],'overdue'=>['Terlambat','#f87171']];
                            [$sl,$sc] = $s[$payment->status] ?? [$payment->status,'#fff'];
                        @endphp
                        <span style="font-size:.68rem; color:{{ $sc }};">{{ $sl }}</span>
                    </div>
                </div>
                @empty
                <p style="color:var(--text-muted); font-size:.85rem; text-align:center; padding:1rem 0; margin:0;">
                    Belum ada tagihan.
                </p>
                @endforelse
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-mk">
                <h6 style="color:var(--text-white); font-weight:600; margin-bottom:1rem;">
                    <i class="bi bi-chat-square-text me-2" style="color:var(--accent);"></i>
                    Keluhan Saya
                </h6>
                @forelse($tenant->complaints as $complaint)
                <div style="display:flex; justify-content:space-between; align-items:center;
                            padding:.6rem 0; border-bottom:1px solid rgba(255,255,255,0.05);">
                    <div>
                        <div style="color:var(--text-white); font-size:.88rem; font-weight:500;">
                            {{ $complaint->title }}
                        </div>
                        <div style="color:var(--text-muted); font-size:.75rem;">
                            {{ $complaint->created_at->format('d M Y') }}
                        </div>
                    </div>
                    @php
                        $c = ['pending'=>['Pending','#fbbf24'],'in_progress'=>['Diproses','#60a5fa'],'resolved'=>['Selesai','#4ade80']];
                        [$cl,$cc] = $c[$complaint->status] ?? [$complaint->status,'#fff'];
                    @endphp
                    <span style="font-size:.72rem; color:{{ $cc }}; font-weight:500;">{{ $cl }}</span>
                </div>
                @empty
                <p style="color:var(--text-muted); font-size:.85rem; text-align:center; padding:1rem 0; margin:0;">
                    Belum ada keluhan.
                </p>
                @endforelse
            </div>
        </div>
    </div>

</div>
</body>
</html>