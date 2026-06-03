@extends('layouts.tenant')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p style="color:var(--text-muted); margin:0; font-size:.88rem;">
        Riwayat laporan pembayaran yang sudah dikirim.
    </p>
    <a href="{{ route('tenant.payment-reports.create') }}"
       style="background:var(--accent); color:#fff; text-decoration:none;
              padding:9px 18px; border-radius:10px; font-size:.88rem;
              font-weight:600; display:inline-flex; align-items:center; gap:8px;">
        <i class="bi bi-plus-lg"></i> Kirim Laporan
    </a>
</div>

<div class="content-card" style="overflow-x: auto; padding: 1.25rem;">
    <table class="table-mk" style="width: 100% !important; border-collapse: collapse !important; text-align: left !important; display: table !important;">
        <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1) !important; display: table-row !important;">
                <th style="padding: 12px 16px !important; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; display: table-cell !important;">Periode</th>
                <th style="padding: 12px 16px !important; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; display: table-cell !important;">Jumlah</th>
                <th style="padding: 12px 16px !important; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; display: table-cell !important;">Metode</th>
                <th style="padding: 12px 16px !important; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; display: table-cell !important;">Bukti</th>
                <th style="padding: 12px 16px !important; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; display: table-cell !important;">Dikirim</th>
                <th style="padding: 12px 16px !important; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; display: table-cell !important;">Status</th>
                <th style="padding: 12px 16px !important; color: var(--text-muted); font-size: 0.85rem; font-weight: 600; display: table-cell !important;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05) !important; display: table-row !important;">
                
                {{-- Kolom Periode --}}
                <td style="padding: 16px !important; color:var(--text-white); font-weight:500; display: table-cell !important; vertical-align: middle;">
                    {{ \Carbon\Carbon::parse($report->period . '-01')->translatedFormat('F Y') }}
                </td>

                {{-- Kolom Jumlah --}}
                <td style="padding: 16px !important; color:var(--accent); font-weight:600; display: table-cell !important; vertical-align: middle;">
                    Rp {{ number_format($report->amount, 0, ',', '.') }}
                </td>

                {{-- Kolom Metode --}}
                <td style="padding: 16px !important; color:var(--text-muted); font-size:.85rem; display: table-cell !important; vertical-align: middle;">
                    {{ $report->method }}
                </td>

                {{-- Kolom Bukti Gambar --}}
                <td style="padding: 16px !important; display: table-cell !important; vertical-align: middle;">
                    <a href="{{ asset('storage/' . $report->proof_image) }}" target="_blank"
                       style="display:inline-block !important; width:44px; height:36px; border-radius:6px;
                              overflow:hidden; border:1px solid rgba(255,255,255,0.1);">
                        <img src="{{ asset('storage/' . $report->proof_image) }}"
                             style="width:100%; height:100%; object-fit:cover;">
                    </a>
                </td>

                {{-- Kolom Dikirim --}}
                <td style="padding: 16px !important; color:var(--text-muted); font-size:.82rem; display: table-cell !important; vertical-align: middle;">
                    {{ $report->created_at->format('d M Y') }}
                    <div style="font-size:.72rem; color: var(--text-muted); margin-top: 2px;">{{ $report->created_at->diffForHumans() }}</div>
                </td>

                {{-- Kolom Status --}}
                <td style="padding: 16px !important; display: table-cell !important; vertical-align: middle;">
                    @if($report->status === 'verified')
                    <span style="background:rgba(34,197,94,0.15) !important; color:#4ade80 !important;
                                 font-size:.72rem; padding:4px 12px; border-radius:20px; font-weight:500; display: inline-block !important;">
                        Terverifikasi
                    </span>
                    @else
                    <span style="background:rgba(234,179,8,0.15) !important; color:#fbbf24 !important;
                                 font-size:.72rem; padding:4px 12px; border-radius:20px; font-weight:500; display: inline-block !important;">
                        Menunggu
                    </span>
                    @endif
                </td>

                {{-- Kolom Aksi --}}
                <td style="padding: 16px !important; display: table-cell !important; vertical-align: middle;">
                    <a href="{{ route('tenant.payment-reports.show', $report) }}"
                       style="padding:6px 14px; background:rgba(255,140,50,0.1);
                              color:var(--accent); border-radius:7px; font-size:.8rem;
                              text-decoration:none; border:1px solid rgba(255,140,50,0.2); display: inline-flex !important; align-items: center !important;">
                        <i class="bi bi-eye me-1"></i> Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr style="display: table-row !important;">
                <td colspan="7" style="text-align:center; padding:3rem; color:var(--text-muted); display: table-cell !important;">
                    <i class="bi bi-file-earmark-x" style="font-size:2.5rem; display:block; margin-bottom:.5rem;"></i>
                    Belum ada laporan yang dikirim.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Navigasi Halaman / Pagination --}}
    @if($reports->hasPages())
    <div style="padding:1rem 1.25rem; border-top:1px solid rgba(255,255,255,0.07); display: block !important;">
        {{ $reports->links() }}
    </div>
    @endif
</div>

@endsection