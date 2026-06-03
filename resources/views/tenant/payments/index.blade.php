@extends('layouts.tenant')

@section('title', 'Tagihan Saya')
@section('page-title', 'Tagihan Saya')

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,0.12);">
                <i class="bi bi-clock-history" style="color:#f87171;"></i>
            </div>
            <div style="font-size:1.5rem; font-weight:700; color:#f87171; line-height:1;">
                {{ $unpaidCount }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">Belum Dibayar</div>
            @if($unpaidTotal > 0)
            <div style="color:#f87171; font-size:.78rem; margin-top:4px; font-weight:500;">
                Rp {{ number_format($unpaidTotal, 0, ',', '.') }}
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(34,197,94,0.12);">
                <i class="bi bi-check-circle-fill" style="color:#4ade80;"></i>
            </div>
            <div style="font-size:1.5rem; font-weight:700; color:#4ade80; line-height:1;">
                Rp {{ number_format($paidTotal, 0, ',', '.') }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">Total Sudah Bayar</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(255,140,50,0.12);">
                <i class="bi bi-receipt" style="color:var(--accent);"></i>
            </div>
            <div style="font-size:1.5rem; font-weight:700; color:var(--text-white); line-height:1;">
                {{ $payments->total() }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">Total Tagihan</div>
        </div>
    </div>
</div>

{{-- Info pembayaran --}}
@if($unpaidCount > 0)
<div style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2);
            border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.5rem;
            display:flex; align-items:center; gap:12px;">
    <i class="bi bi-exclamation-triangle-fill" style="color:#f87171; font-size:1.2rem; flex-shrink:0;"></i>
    <div>
        <div style="color:#f87171; font-weight:600; font-size:.9rem;">
            Kamu memiliki {{ $unpaidCount }} tagihan yang belum dibayar
        </div>
        <div style="color:var(--text-muted); font-size:.8rem; margin-top:2px;">
            Segera lakukan pembayaran dan hubungi admin untuk konfirmasi.
        </div>
    </div>
</div>
@endif

{{-- Tabel tagihan --}}
<div class="content-card" style="overflow-x: auto;"> {{-- Tambah overflow-x agar aman di mobile --}}
    <div class="content-card-header" style="padding: 1rem 1.25rem;">
        <h6 style="color:var(--text-white); font-weight:600; margin:0;">
            <i class="bi bi-list-ul me-2" style="color:var(--accent);"></i>
            Riwayat Tagihan
        </h6>
    </div>
    
    {{-- Update bagian table di bawah ini --}}
    <table class="table-mk" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Periode</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Jumlah</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Jatuh Tempo</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Tgl Bayar</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Metode</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <td style="padding: 16px; color:var(--text-white); font-weight:500;">
                    {{ $payment->due_date->format('F Y') }}
                </td>
                <td style="padding: 16px; color:var(--accent); font-weight:600;">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </td>
                <td style="padding: 16px; color:var(--text-muted); font-size:.85rem;">
                    {{ $payment->due_date->format('d M Y') }}
                    @if($payment->status === 'unpaid' && $payment->due_date->isPast())
                        <div style="color:#f87171; font-size:.72rem; margin-top: 2px;">
                            {{ $payment->due_date->diffForHumans() }}
                        </div>
                    @endif
                </td>
                <td style="padding: 16px; color:var(--text-muted); font-size:.85rem;">
                    {{ $payment->paid_date?->format('d M Y') ?? '—' }}
                </td>
                <td style="padding: 16px; color:var(--text-muted); font-size:.85rem;">
                    {{ $payment->method ?? '—' }}
                </td>
                <td style="padding: 16px;">
                    @php
                        $map = [
                            'paid'    => ['Lunas',       'badge-paid'],
                            'unpaid'  => ['Belum Bayar', 'badge-unpaid'],
                            'overdue' => ['Terlambat',   'badge-overdue'],
                        ];
                        [$lbl, $cls] = $map[$payment->status] ?? [$payment->status, ''];
                    @endphp
                    <span class="{{ $cls }}"
                          style="font-size:.72rem; padding:4px 12px;
                                 border-radius:20px; font-weight:500; display: inline-block;">
                        {{ $lbl }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:3rem; color:var(--text-muted);">
                    <i class="bi bi-inbox" style="font-size:2.5rem; display:block; margin-bottom:.5rem;"></i>
                    Belum ada tagihan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($payments->hasPages())
    <div style="padding:1rem 1.25rem; border-top:1px solid rgba(255,255,255,0.07);">
        {{ $payments->links() }}
    </div>
    @endif
</div>

{{-- Info cara bayar --}}
{{-- Info cara bayar --}}
<div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.07);
            border-radius:14px; padding:1.5rem; margin-top:1.25rem;">

    <h6 style="color:var(--text-white); font-weight:600; margin-bottom:1.25rem;">
        <i class="bi bi-credit-card-2-front me-2" style="color:var(--accent);"></i>
        Cara Pembayaran
    </h6>

    @if($paymentInfo && ($paymentInfo->bank_name || $paymentInfo->qris_image || $paymentInfo->whatsapp))

    <div class="row g-3">

        {{-- Transfer Bank --}}
        @if($paymentInfo->bank_name || $paymentInfo->account_number)
        <div class="col-md-{{ $paymentInfo->qris_image ? '6' : '12' }}">
            <div style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.07);
                        border-radius:12px; padding:1.1rem; height:100%;">
                <div style="color:var(--text-muted); font-size:.75rem; margin-bottom:.75rem;
                            display:flex; align-items:center; gap:6px;">
                    <i class="bi bi-bank" style="color:var(--accent);"></i>
                    Transfer Bank
                </div>

                <div style="font-size:1.1rem; font-weight:700; color:var(--text-white);
                            margin-bottom:4px;">
                    {{ $paymentInfo->bank_name }}
                </div>

                <div style="font-size:1.3rem; font-weight:700; color:var(--accent);
                            letter-spacing:.05em; margin-bottom:4px;">
                    {{ $paymentInfo->account_number }}
                </div>

                <div style="color:var(--text-muted); font-size:.85rem;">
                    a/n {{ $paymentInfo->account_name }}
                </div>

                {{-- Tombol copy --}}
                @if($paymentInfo->account_number)
                <button onclick="copyText('{{ $paymentInfo->account_number }}', this)"
                        style="margin-top:.75rem; background:rgba(255,140,50,0.1);
                               color:var(--accent); border:1px solid rgba(255,140,50,0.2);
                               border-radius:8px; padding:6px 14px; font-size:.78rem;
                               cursor:pointer; display:inline-flex; align-items:center; gap:6px;
                               transition:.2s;">
                    <i class="bi bi-clipboard"></i> Salin Nomor
                </button>
                @endif
            </div>
        </div>
        @endif

        {{-- QRIS --}}
        @if($paymentInfo->qris_image)
        <div class="col-md-{{ $paymentInfo->bank_name ? '6' : '12' }}">
            <div style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.07);
                        border-radius:12px; padding:1.1rem; height:100%; text-align:center;">
                <div style="color:var(--text-muted); font-size:.75rem; margin-bottom:.75rem;
                            display:flex; align-items:center; justify-content:center; gap:6px;">
                    <i class="bi bi-qr-code-scan" style="color:var(--accent);"></i>
                    Bayar via QRIS
                </div>
                <div style="background:white; display:inline-block;
                            padding:12px; border-radius:12px; margin-bottom:.75rem;">
                    <img src="{{ asset('storage/' . $paymentInfo->qris_image) }}"
                         style="width:160px; height:160px; object-fit:contain; display:block;"
                         alt="QRIS Pembayaran">
                </div>
                <div style="color:var(--text-muted); font-size:.75rem;">
                    Scan QR di atas untuk bayar
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- WhatsApp Konfirmasi --}}
    @if($paymentInfo->whatsapp)
    <div style="margin-top:1rem; background:rgba(37,211,102,0.08);
                border:1px solid rgba(37,211,102,0.2); border-radius:12px;
                padding:1rem; display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
        <i class="bi bi-whatsapp" style="font-size:1.5rem; color:#25d366; flex-shrink:0;"></i>
        <div style="flex:1; min-width:0;">
            <div style="color:var(--text-white); font-weight:600; font-size:.9rem;">
                Konfirmasi Pembayaran via WhatsApp
            </div>
            <div style="color:var(--text-muted); font-size:.8rem; margin-top:2px;">
                Setelah transfer, hubungi admin untuk konfirmasi.
            </div>
        </div>
        <a href="https://wa.me/62{{ ltrim($paymentInfo->whatsapp, '0') }}?text=Halo%20Admin%2C%20saya%20{{ urlencode(Auth::guard('tenant')->user()->name) }}%20({{ Auth::guard('tenant')->user()->room->name ?? '' }})%20ingin%20konfirmasi%20pembayaran%20sewa."
           target="_blank"
           style="background:#25d366; color:#fff; text-decoration:none;
                  padding:9px 18px; border-radius:10px; font-size:.85rem;
                  font-weight:600; display:inline-flex; align-items:center; gap:6px;
                  white-space:nowrap; flex-shrink:0; transition:opacity .2s;"
           onmouseover="this.style.opacity='.85'"
           onmouseout="this.style.opacity='1'">
            <i class="bi bi-whatsapp"></i> Hubungi Admin
        </a>
    </div>
    @endif

    {{-- Catatan tambahan --}}
    @if($paymentInfo->notes)
    <div style="margin-top:1rem; background:rgba(255,140,50,0.08);
                border:1px solid rgba(255,140,50,0.15); border-radius:10px;
                padding:.85rem 1rem;">
        <div style="color:var(--accent); font-size:.78rem; font-weight:600; margin-bottom:4px;">
            <i class="bi bi-sticky me-1"></i> Catatan Penting
        </div>
        <p style="color:var(--text-muted); font-size:.85rem; margin:0; line-height:1.7;">
            {{ $paymentInfo->notes }}
        </p>
    </div>
    @endif

    @else
    {{-- Fallback jika belum diisi admin --}}
    <p style="color:var(--text-muted); font-size:.85rem; line-height:1.8; margin:0;">
        Lakukan pembayaran melalui transfer bank atau metode lain yang disepakati,
        kemudian hubungi admin via WhatsApp untuk konfirmasi pembayaran.
        Admin akan memperbarui status tagihan setelah konfirmasi diterima.
    </p>
    @endif

</div>

@endsection