@extends('layouts.admin')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan Pembayaran')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.payment-reports.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">Detail Laporan</span>
</div>

<div class="row g-4">

    {{-- KIRI: Bukti & Info --}}
    <div class="col-lg-7">

        {{-- Foto Bukti --}}
        <div class="content-card mb-3">
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
                     style="max-width:100%; max-height:420px; object-fit:contain;
                            border-radius:12px; border:1px solid rgba(255,255,255,0.07);"
                     alt="Bukti Pembayaran">
            </div>
        </div>

        {{-- Catatan penghuni --}}
        @if($paymentReport->notes)
        <div class="content-card">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-sticky me-2" style="color:var(--accent);"></i>
                    Catatan dari Penghuni
                </h6>
            </div>
            <div class="content-card-body">
                <p style="color:var(--text-muted); line-height:1.8; margin:0; font-size:.92rem;">
                    {{ $paymentReport->notes }}
                </p>
            </div>
        </div>
        @endif

    </div>

    {{-- KANAN: Info & Aksi --}}
    <div class="col-lg-5">

        {{-- Detail Laporan --}}
        <div class="content-card mb-3">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-receipt me-2" style="color:var(--accent);"></i>
                    Detail Laporan
                </h6>
                @if($paymentReport->status === 'verified')
                <span style="background:rgba(34,197,94,0.15); color:#4ade80;
                             font-size:.75rem; padding:4px 12px; border-radius:20px; font-weight:500;">
                    Terverifikasi
                </span>
                @else
                <span style="background:rgba(234,179,8,0.15); color:#fbbf24;
                             font-size:.75rem; padding:4px 12px; border-radius:20px; font-weight:500;">
                    Menunggu
                </span>
                @endif
            </div>
            <div class="content-card-body">
                <div style="display:flex; flex-direction:column; gap:12px; font-size:.88rem;">

                    <div style="display:flex; justify-content:space-between; align-items:center;
                                padding:.6rem 0; border-bottom:1px solid rgba(255,255,255,0.05);">
                        <span style="color:var(--text-muted);">Penghuni</span>
                        <span style="color:var(--text-white); font-weight:600;">
                            {{ $paymentReport->tenant->name ?? '—' }}
                        </span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center;
                                padding:.6rem 0; border-bottom:1px solid rgba(255,255,255,0.05);">
                        <span style="color:var(--text-muted);">Kamar</span>
                        <span style="color:var(--accent); font-weight:600;">
                            {{ $paymentReport->room->name ?? '—' }}
                        </span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center;
                                padding:.6rem 0; border-bottom:1px solid rgba(255,255,255,0.05);">
                        <span style="color:var(--text-muted);">Periode</span>
                        <span style="color:var(--text-white); font-weight:600;">
                            {{ \Carbon\Carbon::parse($paymentReport->period . '-01')->translatedFormat('F Y') }}
                        </span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center;
                                padding:.6rem 0; border-bottom:1px solid rgba(255,255,255,0.05);">
                        <span style="color:var(--text-muted);">Jumlah Dibayar</span>
                        <span style="color:var(--accent); font-weight:700; font-size:1rem;">
                            Rp {{ number_format($paymentReport->amount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center;
                                padding:.6rem 0; border-bottom:1px solid rgba(255,255,255,0.05);">
                        <span style="color:var(--text-muted);">Metode</span>
                        <span style="color:var(--text-white); font-weight:500;">
                            {{ $paymentReport->method }}
                        </span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center;
                                padding:.6rem 0;">
                        <span style="color:var(--text-muted);">Dikirim</span>
                        <span style="color:var(--text-white);">
                            {{ $paymentReport->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                </div>
            </div>
        </div>

        {{-- Tombol Verifikasi --}}
        @if($paymentReport->status === 'pending')
        <div class="content-card">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-shield-check me-2" style="color:var(--accent);"></i>
                    Tindakan Admin
                </h6>
            </div>
            <div class="content-card-body">
                <div style="background:rgba(255,140,50,0.08); border:1px solid rgba(255,140,50,0.15);
                            border-radius:10px; padding:.85rem; margin-bottom:1rem;
                            font-size:.82rem; color:var(--text-muted); line-height:1.7;">
                    <i class="bi bi-info-circle me-1" style="color:var(--accent);"></i>
                    Pastikan sudah mengecek mutasi rekening/QRIS sebelum verifikasi.
                    Setelah diverifikasi, update tagihan penghuni secara manual di menu
                    <strong style="color:var(--accent);">Pembayaran</strong>.
                </div>

                <form method="POST"
                      action="{{ route('admin.payment-reports.verify', $paymentReport) }}"
                      onsubmit="return confirm('Verifikasi laporan ini? Pastikan sudah cek mutasi.')">
                    @csrf
                    <button type="submit"
                            style="width:100%; background:rgba(34,197,94,0.15); color:#4ade80;
                                   border:1px solid rgba(34,197,94,0.3); border-radius:10px;
                                   padding:12px; font-weight:600; cursor:pointer; font-size:.9rem;
                                   display:flex; align-items:center; justify-content:center; gap:8px;"
                            onmouseover="this.style.background='rgba(34,197,94,0.25)'"
                            onmouseout="this.style.background='rgba(34,197,94,0.15)'">
                        <i class="bi bi-check-circle-fill"></i> Tandai Sudah Diverifikasi
                    </button>
                </form>

                <a href="{{ route('admin.payments.index') }}"
                   style="display:block; text-align:center; margin-top:.75rem;
                          color:var(--text-muted); font-size:.82rem; text-decoration:none;">
                    <i class="bi bi-arrow-right me-1"></i>
                    Pergi ke halaman Pembayaran untuk update tagihan
                </a>
            </div>
        </div>
        @else
        <div style="background:rgba(34,197,94,0.08); border:1px solid rgba(34,197,94,0.15);
                    border-radius:14px; padding:1.25rem; text-align:center;">
            <i class="bi bi-check-circle-fill" style="font-size:2rem; color:#4ade80;"></i>
            <p style="color:#4ade80; font-weight:600; margin:.5rem 0 4px;">
                Laporan Terverifikasi
            </p>
            <p style="color:var(--text-muted); font-size:.8rem; margin:0;">
                Diverifikasi pada {{ $paymentReport->updated_at->format('d M Y, H:i') }}
            </p>
        </div>
        @endif

    </div>
</div>

@endsection