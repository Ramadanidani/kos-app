@extends('layouts.tenant')

@section('title', 'Pengajuan Pindah Kamar')
@section('page-title', 'Pengajuan Pindah Kamar')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p style="color:var(--text-muted); margin:0; font-size:.88rem;">
        Riwayat pengajuan pindah kamar kamu.
    </p>
    <a href="{{ route('tenant.transfers.create') }}"
       style="background:var(--accent); color:#fff; text-decoration:none;
              padding:9px 18px; border-radius:10px; font-size:.88rem;
              font-weight:600; display:inline-flex; align-items:center; gap:8px;">
        <i class="bi bi-plus-lg"></i> Ajukan Pindah
    </a>
</div>

{{-- Tabel Pindah Kamar --}}
<div class="content-card" style="overflow-x: auto;"> {{-- Tambah overflow-x agar aman di mobile --}}
    <table class="table-mk" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Dari Kamar</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Ke Kamar</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Alasan</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Tanggal</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Status</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Catatan Admin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transfers as $transfer)
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <td style="padding: 16px; vertical-align: middle;">
                    <span style="font-size:.8rem; padding:4px 12px; display: inline-block;
                                 background:rgba(239,68,68,0.1); color:#f87171;
                                 border-radius:20px; border:1px solid rgba(239,68,68,0.2); font-weight: 500;">
                        {{ $transfer->fromRoom->name ?? '—' }}
                    </span>
                </td>
                <td style="padding: 16px; vertical-align: middle;">
                    <span style="font-size:.8rem; padding:4px 12px; display: inline-block;
                                 background:rgba(34,197,94,0.1); color:#4ade80;
                                 border-radius:20px; border:1px solid rgba(34,197,94,0.2); font-weight: 500;">
                        {{ $transfer->toRoom->name ?? '—' }}
                    </span>
                </td>
                <td style="padding: 16px; color:var(--text-muted); font-size:.82rem; max-width:180px; vertical-align: middle; line-height: 1.4;">
                    {{ Str::limit($transfer->reason ?? '—', 50) }}
                </td>
                <td style="padding: 16px; color:var(--text-muted); font-size:.82rem; vertical-align: middle;">
                    {{ $transfer->created_at->format('d M Y') }}
                </td>
                <td style="padding: 16px; vertical-align: middle;">
                    @php
                        $map = [
                            'pending'  => ['Menunggu',  '#fbbf24', 'rgba(234,179,8,0.15)'],
                            'approved' => ['Disetujui', '#4ade80', 'rgba(34,197,94,0.15)'],
                            'rejected' => ['Ditolak',   '#f87171', 'rgba(239,68,68,0.15)'],
                        ];
                        [$lbl, $color, $bg] = $map[$transfer->status] ?? [$transfer->status, '#fff', 'transparent'];
                    @endphp
                    <span style="font-size:.72rem; padding:4px 12px; border-radius:20px;
                                 font-weight:500; color:{{ $color }}; background:{{ $bg }}; display: inline-block;">
                        {{ $lbl }}
                    </span>
                </td>
                <td style="padding: 16px; color:var(--text-muted); font-size:.82rem; max-width:180px; vertical-align: middle; line-height: 1.4;">
                    {{ $transfer->admin_notes ? Str::limit($transfer->admin_notes, 40) : '—' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:3rem; color:var(--text-muted);">
                    <i class="bi bi-arrow-left-right"
                       style="font-size:2.5rem; display:block; margin-bottom:.5rem;"></i>
                    Belum ada pengajuan pindah kamar.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($transfers->hasPages())
    <div style="padding:1rem 1.25rem; border-top:1px solid rgba(255,255,255,0.07);">
        {{ $transfers->links() }}
    </div>
    @endif
</div>

@endsection