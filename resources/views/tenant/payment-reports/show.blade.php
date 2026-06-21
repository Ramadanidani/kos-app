@extends('layouts.tenant')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan Pembayaran')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('tenant.payment-reports.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">Detail Laporan</span>
</div>

<div class="row g-4">

    {{-- Bukti --}}
    <div class="col-lg-7">
        <div class="content-card">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-image me-2" style="color:var(--accent);"></i>
                    Bukti Pembayaran
                </h6>
                <a href="{{ asset('storage/' . $paymentReport->proof_image) }}"
                   target="_blank"
                   style="color:var(--accent); font-size:.82rem; text-decoration:none;">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka Penuh
                </a>
            </div>
            <div class="content-card-body" style="text-align:center;">
                <img src="{{ asset('storage/' . $paymentReport->proof_image) }}"
                     style="max-width:100%; max-height:380px; object-fit:contain;
                            border-radius:10px;"
                     alt="Bukti Pembayaran">
            </div>
        </div>

        @if($paymentReport->notes)
        <div class="content-card mt-3">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-sticky me-2" style="color:var(--accent);"></i>
                    Catatan Saya
                </h6>
            </div>
            <div class="content-card-body">
                <p style="color:var(--text-muted); line-height:1.8; margin:0;">
                    {{ $paymentReport->notes }}
                </p>
            </div>
        </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="col-lg-5">
        <div class="content-card">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-receipt me-2" style="color:var(--accent);"></i>
                    Info Laporan
                </h6>
                @if($paymentReport->status === 'verified')
                <span style="background:rgba(34,197,94,0.15); color:#4ade80;
                            font-size:.75rem; padding:4px 12px; border-radius:20px; font-weight:500;">
                    Terverifikasi ✓
                </span>
                @elseif($paymentReport->status === 'rejected')
                <span style="background:rgba(239,68,68,0.15); color:#f87171;
                            font-size:.75rem; padding:4px 12px; border-radius:20px; font-weight:500;">
                    Ditolak ✕
                </span>
                @else
                <span style="background:rgba(234,179,8,0.15); color:#fbbf24;
                            font-size:.75rem; padding:4px 12px; border-radius:20px; font-weight:500;">
                    Menunggu Verifikasi
                </span>
                @endif
            </div>
            <div class="content-card-body">
                <div style="display:flex; flex-direction:column; gap:.75rem; font-size:.88rem;">
                    <div style="display:flex; justify-content:space-between;
                                padding:.5rem 0; border-bottom:1px solid rgba(255,255,255,0.05);">
                        <span style="color:var(--text-muted);">Periode</span>
                        <span style="color:var(--text-white); font-weight:600;">
                            {{ \Carbon\Carbon::parse($paymentReport->period . '-01')->translatedFormat('F Y') }}
                        </span>
                    </div>
                    <div style="display:flex; justify-content:space-between;
                                padding:.5rem 0; border-bottom:1px solid rgba(255,255,255,0.05);">
                        <span style="color:var(--text-muted);">Jumlah</span>
                        <span style="color:var(--accent); font-weight:700; font-size:1rem;">
                            Rp {{ number_format($paymentReport->amount, 0, ',', '.') }}
                        </span>
                    </div>
                    <div style="display:flex; justify-content:space-between;
                                padding:.5rem 0; border-bottom:1px solid rgba(255,255,255,0.05);">
                        <span style="color:var(--text-muted);">Metode</span>
                        <span style="color:var(--text-white);">{{ $paymentReport->method }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding:.5rem 0;">
                        <span style="color:var(--text-muted);">Dikirim</span>
                        <span style="color:var(--text-white);">
                            {{ $paymentReport->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>

                @if($paymentReport->status === 'verified')
                <div style="margin-top:1rem; background:rgba(34,197,94,0.08);
                            border:1px solid rgba(34,197,94,0.15); border-radius:10px;
                            padding:.85rem; text-align:center;">
                    <i class="bi bi-check-circle-fill" style="color:#4ade80; font-size:1.5rem;"></i>
                    <p style="color:#4ade80; margin:.4rem 0 2px; font-weight:600; font-size:.9rem;">
                        Laporan Sudah Diverifikasi
                    </p>
                    <p style="color:var(--text-muted); font-size:.78rem; margin:0;">
                        Diverifikasi {{ $paymentReport->updated_at->diffForHumans() }}
                    </p>
                </div>
                @elseif($paymentReport->status === 'rejected')
                <div style="margin-top:1rem; background:rgba(239,68,68,0.08);
                            border:1px solid rgba(239,68,68,0.15); border-radius:10px;
                            padding:.85rem; text-align:center;">
                    <i class="bi bi-x-circle-fill" style="color:#f87171; font-size:1.5rem;"></i>
                    <p style="color:#f87171; margin:.4rem 0 2px; font-weight:600; font-size:.9rem;">
                        Laporan Ditolak
                    </p>
                    <p style="color:var(--text-muted); font-size:.78rem; margin:0;">
                        Ditolak {{ $paymentReport->updated_at->diffForHumans() }}
                    </p>
                </div>

                {{-- Alasan penolakan --}}
                @if($paymentReport->rejection_reason)
                <div style="margin-top:.75rem; background:rgba(239,68,68,0.06);
                            border:1px solid rgba(239,68,68,0.15); border-radius:10px;
                            padding:.85rem 1rem;">
                    <div style="color:#f87171; font-size:.78rem; font-weight:600; margin-bottom:4px;">
                        <i class="bi bi-info-circle me-1"></i> Alasan Penolakan
                    </div>
                    <p style="color:var(--text-muted); font-size:.85rem; margin:0; line-height:1.7;">
                        {{ $paymentReport->rejection_reason }}
                    </p>
                </div>
                @endif

                {{-- Tombol kirim ulang --}}
                <a href="{{ route('tenant.payment-reports.create') }}"
                style="display:block; text-align:center; margin-top:.75rem;
                        background:var(--accent); color:#fff; text-decoration:none;
                        padding:10px; border-radius:10px; font-size:.85rem; font-weight:600;">
                    <i class="bi bi-arrow-repeat me-1"></i> Kirim Laporan Baru
                </a>
                @else
                <div style="margin-top:1rem; background:rgba(234,179,8,0.08);
                            border:1px solid rgba(234,179,8,0.15); border-radius:10px;
                            padding:.85rem; text-align:center;">
                    <i class="bi bi-hourglass-split" style="color:#fbbf24; font-size:1.5rem;"></i>
                    <p style="color:#fbbf24; margin:.4rem 0 2px; font-weight:600; font-size:.9rem;">
                        Menunggu Verifikasi Admin
                    </p>
                    <p style="color:var(--text-muted); font-size:.78rem; margin:0;">
                        Admin akan mengecek dan memverifikasi laporan ini.
                    </p>
                </div>
                @endif

            </div>
        </div>
    </div>

</div>

@endsection