@extends('layouts.admin')

@section('title', $tenant->name)
@section('page-title', 'Detail Penghuni')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.tenants.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">{{ $tenant->name }}</span>
</div>

<div class="row g-4">

    {{-- KIRI: Profil --}}
    <div class="col-lg-4">
        <div class="content-card p-4 text-center mb-3">

            {{-- Avatar --}}
            <div style="width:72px; height:72px; background:rgba(255,140,50,0.15);
                        border-radius:50%; display:flex; align-items:center;
                        justify-content:center; margin:0 auto 12px;
                        color:var(--accent); font-size:1.8rem; font-weight:700;">
                {{ strtoupper(substr($tenant->name, 0, 1)) }}
            </div>

            <h5 style="color:var(--text-white); font-weight:700; margin-bottom:4px;">
                {{ $tenant->name }}
            </h5>

            @if($tenant->status === 'active')
                <span style="background:rgba(34,197,94,0.15); color:#4ade80;
                             font-size:.75rem; padding:4px 12px; border-radius:20px; font-weight:500;">
                    Aktif
                </span>
            @else
                <span style="background:rgba(239,68,68,0.15); color:#f87171;
                             font-size:.75rem; padding:4px 12px; border-radius:20px; font-weight:500;">
                    Tidak Aktif
                </span>
            @endif

            <hr style="border-color:rgba(255,255,255,0.07); margin:1rem 0;">

            <div style="text-align:left; font-size:.85rem;">
                <div style="display:flex; gap:10px; margin-bottom:10px;">
                    <i class="bi bi-phone" style="color:var(--accent); width:16px;"></i>
                    <span style="color:var(--text-muted);">{{ $tenant->phone }}</span>
                </div>
                @if($tenant->id_card)
                <div style="display:flex; gap:10px; margin-bottom:10px;">
                    <i class="bi bi-card-text" style="color:var(--accent); width:16px;"></i>
                    <span style="color:var(--text-muted);">{{ $tenant->id_card }}</span>
                </div>
                @endif
                @if($tenant->room)
                <div style="display:flex; gap:10px; margin-bottom:10px;">
                    <i class="bi bi-door-open" style="color:var(--accent); width:16px;"></i>
                    <span style="color:var(--text-muted);">{{ $tenant->room->name }}</span>
                </div>
                @endif
                <div style="display:flex; gap:10px; margin-bottom:10px;">
                    <i class="bi bi-calendar" style="color:var(--accent); width:16px;"></i>
                    <span style="color:var(--text-muted);">
                        {{ $tenant->start_date?->format('d M Y') ?? '—' }}
                        @if($tenant->end_date) — {{ $tenant->end_date->format('d M Y') }} @endif
                    </span>
                </div>
                @if($tenant->notes)
                <div style="display:flex; gap:10px;">
                    <i class="bi bi-sticky" style="color:var(--accent); width:16px;"></i>
                    <span style="color:var(--text-muted);">{{ $tenant->notes }}</span>
                </div>
                @endif
            </div>

            <hr style="border-color:rgba(255,255,255,0.07); margin:1rem 0;">

            <a href="{{ route('admin.tenants.edit', $tenant) }}"
               style="display:block; padding:9px; background:rgba(255,140,50,0.12);
                      color:var(--accent); border:1px solid rgba(255,140,50,0.25);
                      border-radius:10px; text-decoration:none; font-size:.85rem;
                      font-weight:500; transition:background .2s;"
               onmouseover="this.style.background='rgba(255,140,50,0.22)'"
               onmouseout="this.style.background='rgba(255,140,50,0.12)'">
                <i class="bi bi-pencil me-1"></i> Edit Data
            </a>
            {{-- Tombol Reminder WA --}}
            <form method="POST"
                action="{{ route('admin.whatsapp.tenant-reminder', $tenant) }}"
                onsubmit="return confirm('Kirim reminder ke {{ $tenant->name }}?')"
                style="margin-top:.5rem;">
                @csrf
                <button type="submit"
                        style="display:block; width:100%; padding:9px;
                            background:rgba(37,211,102,0.1); color:#25d366;
                            border:1px solid rgba(37,211,102,0.2); border-radius:10px;
                            font-size:.85rem; font-weight:500; cursor:pointer;
                            transition:background .2s;"
                        onmouseover="this.style.background='rgba(37,211,102,0.2)'"
                        onmouseout="this.style.background='rgba(37,211,102,0.1)'">
                    <i class="bi bi-whatsapp me-1"></i> Kirim Reminder WA
                </button>
            </form>
        </div>
    </div>

    {{-- KANAN: Riwayat --}}
    <div class="col-lg-8">

        {{-- Pembayaran Terakhir --}}
        <div class="content-card mb-3">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-credit-card me-2" style="color:var(--accent);"></i>
                    Pembayaran Terakhir
                </h6>
                <a href="{{ route('admin.payments.index') }}" class="link-accent">Lihat Semua →</a>
            </div>
            <div class="content-card-body">
                @forelse($tenant->payments as $payment)
                <div class="list-item-mk">
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
                            $pBadge = ['paid'=>['Lunas','badge-paid'],'unpaid'=>['Belum Bayar','badge-unpaid'],'overdue'=>['Terlambat','badge-overdue']];
                            [$pLabel,$pClass] = $pBadge[$payment->status] ?? [$payment->status,''];
                        @endphp
                        <span class="{{ $pClass }}"
                              style="font-size:.68rem; padding:2px 8px; border-radius:20px; font-weight:500;">
                            {{ $pLabel }}
                        </span>
                    </div>
                </div>
                @empty
                <p style="color:var(--text-muted); font-size:.85rem; text-align:center; padding:1rem 0;">
                    Belum ada riwayat pembayaran.
                </p>
                @endforelse
            </div>
        </div>

        {{-- Keluhan Terakhir --}}
        <div class="content-card">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-chat-square-text me-2" style="color:var(--accent);"></i>
                    Keluhan Terakhir
                </h6>
                <a href="{{ route('admin.complaints.index') }}" class="link-accent">Lihat Semua →</a>
            </div>
            <div class="content-card-body">
                @forelse($tenant->complaints as $complaint)
                <div class="list-item-mk">
                    <div style="flex-grow:1;">
                        <div style="color:var(--text-white); font-size:.88rem; font-weight:500;">
                            {{ $complaint->title }}
                        </div>
                        <div style="color:var(--text-muted); font-size:.75rem;">
                            {{ $complaint->created_at->format('d M Y') }}
                        </div>
                    </div>
                    @php
                        $cBadge = ['pending'=>['Pending','badge-pending'],'in_progress'=>['Diproses','badge-in_progress'],'resolved'=>['Selesai','badge-resolved']];
                        [$cLabel,$cClass] = $cBadge[$complaint->status] ?? [$complaint->status,''];
                    @endphp
                    <span class="{{ $cClass }}"
                          style="font-size:.68rem; padding:3px 9px; border-radius:20px; font-weight:500; white-space:nowrap;">
                        {{ $cLabel }}
                    </span>
                </div>
                @empty
                <p style="color:var(--text-muted); font-size:.85rem; text-align:center; padding:1rem 0;">
                    Belum ada keluhan.
                </p>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection