@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')

{{-- Header --}}
<div class="mb-4">
    <h4 style="font-weight:700; color:var(--text-white); margin-bottom:4px;">
        Dashboard Admin
    </h4>
    <p style="color:var(--text-muted); margin:0; font-size:.9rem;">
        Selamat datang kembali, {{ Auth::user()->name }}!
    </p>
</div>

{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(255,140,50,0.12);">
                <i class="bi bi-door-open-fill" style="color:var(--accent);"></i>
            </div>
            <div style="font-size:2rem; font-weight:700; color:var(--text-white); line-height:1;">
                {{ $totalRooms }}
            </div>
            <div style="color:var(--text-muted); font-size:.85rem; margin-top:4px;">Total Kamar</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(34,197,94,0.12);">
                <i class="bi bi-people-fill" style="color:#4ade80;"></i>
            </div>
            <div style="font-size:2rem; font-weight:700; color:var(--text-white); line-height:1;">
                {{ $activeTenants }}
            </div>
            <div style="color:var(--text-muted); font-size:.85rem; margin-top:4px;">Penghuni Aktif</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,0.12);">
                <i class="bi bi-credit-card-fill" style="color:#f87171;"></i>
            </div>
            <div style="font-size:2rem; font-weight:700; color:#f87171; line-height:1;">
                {{ $unpaidCount }}
            </div>
            <div style="color:var(--text-muted); font-size:.85rem; margin-top:4px;">
                Tagihan Belum Dibayar
            </div>
            @if($unpaidCount > 0)
            <div style="color:#f87171; font-size:.82rem; margin-top:6px; font-weight:500;">
                Rp {{ number_format($unpaidTotal, 0, ',', '.') }}
            </div>
            @endif
        </div>
    </div>

</div>

{{-- ── KELUHAN & PEMBAYARAN ── --}}
<div class="row g-3">

    {{-- Keluhan Pending --}}
    <div class="col-lg-6">
        <div class="content-card">
            <div class="content-card-header">
                <div>
                    <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                        Keluhan Pending
                    </h6>
                    @if($pendingComplaints->count() > 0)
                    <small style="color:var(--text-muted);">
                        {{ $pendingComplaints->count() }} keluhan menunggu
                    </small>
                    @endif
                </div>
                <a href="{{ route('admin.complaints.index') }}" class="link-accent">
                    Lihat Semua →
                </a>
            </div>
            <div class="content-card-body">
                @forelse($pendingComplaints as $complaint)
                <div class="list-item-mk">
                    <div style="flex-grow:1; min-width:0;">
                        <div style="color:var(--text-white); font-weight:600;
                                    font-size:.9rem; margin-bottom:3px;
                                    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $complaint->title }}
                        </div>
                        <div style="color:var(--text-muted); font-size:.78rem;">
                            {{ $complaint->room->name ?? '-' }} ·
                            {{ $complaint->tenant->name ?? '-' }} ·
                            {{ $complaint->created_at->format('d M Y') }}
                        </div>
                    </div>
                    <span class="badge-pending"
                          style="font-size:.68rem; padding:3px 9px;
                                 border-radius:20px; white-space:nowrap; font-weight:500;">
                        Pending
                    </span>
                </div>
                @empty
                <div style="text-align:center; padding:2rem 0; color:var(--text-muted);">
                    <i class="bi bi-check-circle" style="font-size:2rem; color:#4ade80;"></i>
                    <p style="margin-top:.5rem; font-size:.88rem;">Tidak ada keluhan pending.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Pembayaran Terbaru --}}
    <div class="col-lg-6">
        <div class="content-card">
            <div class="content-card-header">
                <div>
                    <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                        Pembayaran Terbaru
                    </h6>
                    <small style="color:var(--text-muted);">Pembayaran yang sudah lunas</small>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="link-accent">
                    Lihat Semua →
                </a>
            </div>
            <div class="content-card-body">
                @forelse($recentPayments as $payment)
                <div class="list-item-mk">
                    <div style="flex-grow:1; min-width:0;">
                        <div style="color:var(--text-white); font-weight:600;
                                    font-size:.9rem; margin-bottom:3px;">
                            {{ $payment->tenant->name ?? '-' }}
                        </div>
                        <div style="color:var(--text-muted); font-size:.78rem;">
                            {{ $payment->room->name ?? '-' }} ·
                            {{ $payment->paid_date?->format('d M Y') ?? '-' }}
                        </div>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <div style="color:#4ade80; font-weight:600; font-size:.88rem;">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </div>
                        <span class="badge-paid"
                              style="font-size:.68rem; padding:3px 9px;
                                     border-radius:20px; font-weight:500;">
                            Lunas
                        </span>
                    </div>
                </div>
                @empty
                <div style="text-align:center; padding:2rem 0; color:var(--text-muted);">
                    <i class="bi bi-inbox" style="font-size:2rem;"></i>
                    <p style="margin-top:.5rem; font-size:.88rem;">Belum ada pembayaran.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection