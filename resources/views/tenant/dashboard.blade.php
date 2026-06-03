@extends('layouts.tenant')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Header --}}
<div class="mb-4">
    <h4 style="font-weight:700; color:var(--text-white); margin-bottom:4px;">
        Halo, {{ Auth::guard('tenant')->user()->name }}! 👋
    </h4>
    <p style="color:var(--text-muted); margin:0; font-size:.9rem;">
        Selamat datang di portal penghuni ManageMyKos.
    </p>
</div>

{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">

    {{-- Info Kamar --}}
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(255,140,50,0.12);">
                <i class="bi bi-door-open-fill" style="color:var(--accent);"></i>
            </div>
            <div style="font-size:1.3rem; font-weight:700; color:var(--text-white); line-height:1;">
                {{ $tenant->room->name ?? '—' }}
            </div>
            <div style="color:var(--text-muted); font-size:.8rem; margin-top:4px;">Kamar Saya</div>
            @if($tenant->room)
            <div style="color:var(--text-muted); font-size:.75rem; margin-top:4px;">
                {{ $tenant->room->type }} · Lantai {{ $tenant->room->floor }}
            </div>
            @endif
        </div>
    </div>

    {{-- Tagihan Belum Bayar --}}
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon"
                 style="background:{{ $unpaidCount > 0 ? 'rgba(239,68,68,0.12)' : 'rgba(34,197,94,0.12)' }};">
                <i class="bi bi-credit-card-fill"
                   style="color:{{ $unpaidCount > 0 ? '#f87171' : '#4ade80' }};"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; line-height:1;
                        color:{{ $unpaidCount > 0 ? '#f87171' : '#4ade80' }};">
                {{ $unpaidCount }}
            </div>
            <div style="color:var(--text-muted); font-size:.8rem; margin-top:4px;">
                Tagihan Belum Bayar
            </div>
            @if($unpaidCount > 0)
            <div style="color:#f87171; font-size:.75rem; margin-top:4px; font-weight:500;">
                Rp {{ number_format($unpaidTotal, 0, ',', '.') }}
            </div>
            @endif
        </div>
    </div>

    {{-- Total Sudah Bayar --}}
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(34,197,94,0.12);">
                <i class="bi bi-check-circle-fill" style="color:#4ade80;"></i>
            </div>
            <div style="font-size:1.3rem; font-weight:700; color:#4ade80; line-height:1;">
                Rp {{ number_format($paidTotal, 0, ',', '.') }}
            </div>
            <div style="color:var(--text-muted); font-size:.8rem; margin-top:4px;">
                Total Sudah Dibayar
            </div>
        </div>
    </div>

    {{-- Mulai Sewa --}}
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(96,165,250,0.12);">
                <i class="bi bi-calendar-check-fill" style="color:#60a5fa;"></i>
            </div>
            <div style="font-size:1.1rem; font-weight:700; color:var(--text-white); line-height:1;">
                {{ $tenant->start_date?->format('d M Y') ?? '—' }}
            </div>
            <div style="color:var(--text-muted); font-size:.8rem; margin-top:4px;">Mulai Sewa</div>
            @if($tenant->start_date)
            <div style="color:var(--text-muted); font-size:.75rem; margin-top:4px;">
                {{ $tenant->start_date->diffForHumans() }}
            </div>
            @endif
        </div>
    </div>

</div>

{{-- ── INFO KAMAR + TAGIHAN ── --}}
<div class="row g-3 mb-3">

    {{-- Detail Kamar --}}
    <div class="col-lg-5">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-house-fill me-2" style="color:var(--accent);"></i>
                    Info Kamar Saya
                </h6>
            </div>
            <div class="content-card-body">
                @if($tenant->room)
                <div style="display:flex; flex-direction:column; gap:12px;">

                    <div style="display:flex; gap:12px; align-items:center;">
                        <div style="width:40px; height:40px; background:rgba(255,140,50,0.1);
                                    border-radius:10px; display:flex; align-items:center;
                                    justify-content:center; flex-shrink:0;">
                            <i class="bi bi-tag-fill" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <div style="color:var(--text-muted); font-size:.72rem;">Tipe Kamar</div>
                            <div style="color:var(--text-white); font-weight:500; font-size:.9rem;">
                                {{ $tenant->room->type }}
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; gap:12px; align-items:center;">
                        <div style="width:40px; height:40px; background:rgba(255,140,50,0.1);
                                    border-radius:10px; display:flex; align-items:center;
                                    justify-content:center; flex-shrink:0;">
                            <i class="bi bi-aspect-ratio-fill" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <div style="color:var(--text-muted); font-size:.72rem;">Ukuran</div>
                            <div style="color:var(--text-white); font-weight:500; font-size:.9rem;">
                                {{ $tenant->room->size }} m²
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; gap:12px; align-items:center;">
                        <div style="width:40px; height:40px; background:rgba(255,140,50,0.1);
                                    border-radius:10px; display:flex; align-items:center;
                                    justify-content:center; flex-shrink:0;">
                            <i class="bi bi-building-fill" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <div style="color:var(--text-muted); font-size:.72rem;">Lantai</div>
                            <div style="color:var(--text-white); font-weight:500; font-size:.9rem;">
                                Lantai {{ $tenant->room->floor }}
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; gap:12px; align-items:center;">
                        <div style="width:40px; height:40px; background:rgba(255,140,50,0.1);
                                    border-radius:10px; display:flex; align-items:center;
                                    justify-content:center; flex-shrink:0;">
                            <i class="bi bi-cash-coin" style="color:var(--accent);"></i>
                        </div>
                        <div>
                            <div style="color:var(--text-muted); font-size:.72rem;">Harga Sewa</div>
                            <div style="color:var(--accent); font-weight:600; font-size:.9rem;">
                                Rp {{ number_format($tenant->room->price, 0, ',', '.') }}/bulan
                            </div>
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    @if($tenant->room->facilities && count($tenant->room->facilities) > 0)
                    <div>
                        <div style="color:var(--text-muted); font-size:.72rem; margin-bottom:6px;">
                            Fasilitas
                        </div>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($tenant->room->facilities as $f)
                            <span style="font-size:.72rem; padding:3px 9px;
                                         background:rgba(255,255,255,0.05);
                                         border:1px solid rgba(255,255,255,0.08);
                                         border-radius:20px; color:var(--text-muted);">
                                <i class="bi bi-check2 me-1" style="color:var(--accent);"></i>{{ $f }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
                @else
                <div style="text-align:center; padding:2rem 0; color:var(--text-muted);">
                    <i class="bi bi-house-x" style="font-size:2.5rem;"></i>
                    <p style="margin-top:.5rem; font-size:.85rem;">Belum ada kamar.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tagihan Terbaru --}}
    <div class="col-lg-7">
        <div class="content-card h-100">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-credit-card me-2" style="color:var(--accent);"></i>
                    Tagihan Terbaru
                </h6>
                <a href="{{ route('tenant.payments.index') }}" class="link-accent">
                    Lihat Semua →
                </a>
            </div>
            <div class="content-card-body">
                @forelse($tenant->payments as $payment)
                <div class="list-item-mk">
                    <div>
                        <div style="color:var(--text-white); font-weight:600; font-size:.9rem;">
                            Tagihan {{ $payment->due_date->format('F Y') }}
                        </div>
                        <div style="color:var(--text-muted); font-size:.75rem; margin-top:2px;">
                            Jatuh tempo: {{ $payment->due_date->format('d M Y') }}
                            @if($payment->paid_date)
                                · Dibayar: {{ $payment->paid_date->format('d M Y') }}
                            @endif
                        </div>
                    </div>
                    <div style="text-align:right; flex-shrink:0;">
                        <div style="color:var(--accent); font-weight:700; font-size:.9rem;">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </div>
                        @php
                            $map = [
                                'paid'    => ['Lunas',       'badge-paid'],
                                'unpaid'  => ['Belum Bayar', 'badge-unpaid'],
                                'overdue' => ['Terlambat',   'badge-overdue'],
                            ];
                            [$lbl, $cls] = $map[$payment->status] ?? [$payment->status, ''];
                        @endphp
                        <span class="{{ $cls }}"
                              style="font-size:.68rem; padding:2px 8px;
                                     border-radius:20px; font-weight:500;">
                            {{ $lbl }}
                        </span>
                    </div>
                </div>
                @empty
                <div style="text-align:center; padding:2rem 0; color:var(--text-muted);">
                    <i class="bi bi-inbox" style="font-size:2rem;"></i>
                    <p style="margin-top:.5rem; font-size:.85rem;">Belum ada tagihan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

{{-- ── KELUHAN TERBARU ── --}}
<div class="content-card">
    <div class="content-card-header">
        <h6 style="color:var(--text-white); font-weight:600; margin:0;">
            <i class="bi bi-chat-square-text me-2" style="color:var(--accent);"></i>
            Keluhan Terbaru
        </h6>
        <a href="{{ route('tenant.complaints.index') }}" class="link-accent">
            Lihat Semua →
        </a>
    </div>
    <div class="content-card-body">
        @forelse($tenant->complaints as $complaint)
        <div class="list-item-mk">
            <div style="flex-grow:1;">
                <div style="color:var(--text-white); font-weight:600; font-size:.9rem;">
                    {{ $complaint->title }}
                </div>
                <div style="color:var(--text-muted); font-size:.75rem; margin-top:2px;">
                    {{ $complaint->created_at->format('d M Y') }}
                    @if($complaint->admin_notes)
                        · <span style="color:#60a5fa;">Ada balasan admin</span>
                    @endif
                </div>
            </div>
            @php
                $cmap = [
                    'pending'     => ['Pending',  'badge-pending'],
                    'in_progress' => ['Diproses', 'badge-in_progress'],
                    'resolved'    => ['Selesai',  'badge-resolved'],
                ];
                [$clbl, $ccls] = $cmap[$complaint->status] ?? [$complaint->status, ''];
            @endphp
            <span class="{{ $ccls }}"
                  style="font-size:.72rem; padding:3px 9px;
                         border-radius:20px; font-weight:500; white-space:nowrap;">
                {{ $clbl }}
            </span>
        </div>
        @empty
        <div style="text-align:center; padding:2rem 0; color:var(--text-muted);">
            <i class="bi bi-chat-square-check" style="font-size:2rem; color:#4ade80;"></i>
            <p style="margin-top:.5rem; font-size:.85rem;">Tidak ada keluhan aktif.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection