@extends('layouts.admin')

@section('title', 'WhatsApp Reminder')
@section('page-title', 'WhatsApp Reminder')

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(37,211,102,0.12);">
                <i class="bi bi-whatsapp" style="color:#25d366;"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; color:var(--text-white); line-height:1;">
                {{ $unpaidPayments->count() }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">
                Total Perlu Diingatkan
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,0.12);">
                <i class="bi bi-exclamation-triangle-fill" style="color:#f87171;"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; color:#f87171; line-height:1;">
                {{ $overdueCount }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">
                Tagihan Terlambat
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(234,179,8,0.12);">
                <i class="bi bi-clock-history" style="color:#fbbf24;"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; color:#fbbf24; line-height:1;">
                {{ $unpaidCount }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">
                Belum Bayar
            </div>
        </div>
    </div>
</div>

{{-- Bulk Action --}}
<div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.07);
            border-radius:14px; padding:1.25rem; margin-bottom:1.5rem;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h6 style="color:var(--text-white); font-weight:600; margin-bottom:4px;">
                <i class="bi bi-broadcast me-2" style="color:#25d366;"></i>
                Kirim Reminder Sekaligus
            </h6>
            <p style="color:var(--text-muted); font-size:.82rem; margin:0;">
                Kirim reminder ke semua {{ $unpaidPayments->count() }} penghuni
                yang belum membayar sekaligus.
            </p>
        </div>
        <form method="POST" action="{{ route('admin.whatsapp.bulk-reminder') }}"
              onsubmit="return confirm('Kirim reminder ke semua {{ $unpaidPayments->count() }} penghuni yang belum bayar?')">
            @csrf
            <button type="submit"
                    style="background:#25d366; color:#fff; border:none;
                           border-radius:10px; padding:10px 20px; font-weight:600;
                           cursor:pointer; font-size:.9rem; display:flex;
                           align-items:center; gap:8px; transition:opacity .2s;"
                    onmouseover="this.style.opacity='.85'"
                    onmouseout="this.style.opacity='1'"
                    {{ $unpaidPayments->isEmpty() ? 'disabled' : '' }}>
                <i class="bi bi-whatsapp"></i>
                Kirim ke Semua ({{ $unpaidPayments->count() }})
            </button>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="content-card">
    <div class="content-card-header">
        <h6 style="color:var(--text-white); font-weight:600; margin:0;">
            <i class="bi bi-list-ul me-2" style="color:var(--accent);"></i>
            Daftar Tagihan Belum Bayar
        </h6>
    </div>
    <table class="table-mk">
        <thead>
            <tr>
                <th>Penghuni</th>
                <th>Kamar</th>
                <th>Tagihan</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
                <th>Kirim Reminder</th>
            </tr>
        </thead>
        <tbody>
            @forelse($unpaidPayments as $payment)
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px; height:32px; background:rgba(255,140,50,0.15);
                                    border-radius:50%; display:flex; align-items:center;
                                    justify-content:center; color:var(--accent);
                                    font-weight:700; font-size:.8rem; flex-shrink:0;">
                            {{ strtoupper(substr($payment->tenant->name ?? 'T', 0, 1)) }}
                        </div>
                        <div>
                            <div style="color:var(--text-white); font-weight:500; font-size:.88rem;">
                                {{ $payment->tenant->name ?? '—' }}
                            </div>
                            <div style="color:var(--text-muted); font-size:.72rem;">
                                <i class="bi bi-phone me-1"></i>
                                {{ $payment->tenant->phone ?? '—' }}
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <span style="font-size:.8rem; padding:3px 10px;
                                 background:rgba(255,140,50,0.1); color:var(--accent);
                                 border-radius:20px; border:1px solid rgba(255,140,50,0.2);">
                        {{ $payment->room->name ?? '—' }}
                    </span>
                </td>
                <td style="color:var(--accent); font-weight:600; font-size:.9rem;">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </td>
                <td style="color:var(--text-muted); font-size:.85rem;">
                    {{ $payment->due_date->format('d M Y') }}
                    @if($payment->due_date->isPast())
                    <div style="color:#f87171; font-size:.72rem;">
                        {{ $payment->due_date->diffForHumans() }}
                    </div>
                    @endif
                </td>
                <td>
                    @if($payment->status === 'overdue')
                    <span style="background:rgba(239,68,68,0.15); color:#f87171;
                                 font-size:.72rem; padding:3px 10px;
                                 border-radius:20px; font-weight:500;">
                        Terlambat
                    </span>
                    @else
                    <span style="background:rgba(234,179,8,0.15); color:#fbbf24;
                                 font-size:.72rem; padding:3px 10px;
                                 border-radius:20px; font-weight:500;">
                        Belum Bayar
                    </span>
                    @endif
                </td>
                <td>
                    <form method="POST"
                          action="{{ route('admin.whatsapp.reminder', $payment) }}"
                          onsubmit="return confirm('Kirim reminder ke {{ $payment->tenant->name }}?')">
                        @csrf
                        <button type="submit"
                                style="padding:6px 14px; background:#25d366; color:#fff;
                                       border-radius:8px; font-size:.8rem; border:none;
                                       cursor:pointer; display:flex; align-items:center;
                                       gap:6px; transition:opacity .2s;"
                                onmouseover="this.style.opacity='.85'"
                                onmouseout="this.style.opacity='1'">
                            <i class="bi bi-whatsapp"></i> Kirim
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:3rem; color:var(--text-muted);">
                    <i class="bi bi-check-circle"
                       style="font-size:2.5rem; color:#4ade80; display:block; margin-bottom:.5rem;"></i>
                    Semua penghuni sudah membayar! 🎉
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection