@extends('layouts.tenant')

@section('title', 'Keluhan Saya')
@section('page-title', 'Keluhan Saya')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p style="color:var(--text-muted); margin:0; font-size:.88rem;">
        Total {{ $complaints->total() }} keluhan
    </p>
    <a href="{{ route('tenant.complaints.create') }}"
       style="background:var(--accent); color:#fff; text-decoration:none;
              padding:9px 18px; border-radius:10px; font-size:.88rem;
              font-weight:600; display:inline-flex; align-items:center; gap:8px;">
        <i class="bi bi-plus-lg"></i> Buat Keluhan
    </a>
</div>

{{-- Tabel Keluhan --}}
<div class="content-card" style="overflow-x: auto;"> {{-- Tambah overflow-x agar aman di mobile --}}
    <table class="table-mk" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Judul Keluhan</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Tanggal</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Status</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Balasan Admin</th>
                <th style="padding: 12px 16px; color: var(--text-muted); font-size: 0.85rem; font-weight: 600;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $complaint)
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <td style="padding: 16px;">
                    <div style="color:var(--text-white); font-weight:500; font-size:.9rem;">
                        {{ $complaint->title }}
                    </div>
                    <div style="color:var(--text-muted); font-size:.75rem; margin-top:4px;"> {{-- Sedikit dinaikkan margin atasnya --}}
                        {{ Str::limit($complaint->description, 60) }}
                    </div>
                </td>
                <td style="padding: 16px; color:var(--text-muted); font-size:.85rem; vertical-align: middle;">
                    {{ $complaint->created_at->format('d M Y') }}
                    <div style="font-size:.72rem; margin-top: 2px;">{{ $complaint->created_at->diffForHumans() }}</div>
                </td>
                <td style="padding: 16px; vertical-align: middle;">
                    @php
                        $map = [
                            'pending'     => ['Pending',  'badge-pending'],
                            'in_progress' => ['Diproses', 'badge-in_progress'],
                            'resolved'    => ['Selesai',  'badge-resolved'],
                        ];
                        [$lbl, $cls] = $map[$complaint->status] ?? [$complaint->status, ''];
                    @endphp
                    <span class="{{ $cls }}"
                          style="font-size:.72rem; padding:4px 12px;
                                 border-radius:20px; font-weight:500; display: inline-block;">
                        {{ $lbl }}
                    </span>
                </td>
                <td style="padding: 16px; vertical-align: middle;">
                    @if($complaint->admin_notes)
                        <span style="color:#60a5fa; font-size:.8rem; display: inline-flex; align-items: center;">
                            <i class="bi bi-chat-dots me-1"></i>Ada balasan
                        </span>
                    @else
                        <span style="color:var(--text-muted); font-size:.8rem;">—</span>
                    @endif
                </td>
                <td style="padding: 16px; vertical-align: middle;">
                    <a href="{{ route('tenant.complaints.show', $complaint) }}"
                       style="padding:6px 14px; background:rgba(255,140,50,0.1);
                              color:var(--accent); border-radius:7px; font-size:.8rem;
                              text-decoration:none; border:1px solid rgba(255,140,50,0.2);
                              display: inline-flex; align-items: center;">
                        <i class="bi bi-eye me-1"></i> Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center; padding:3rem; color:var(--text-muted);">
                    <i class="bi bi-chat-square-check"
                       style="font-size:2.5rem; display:block; margin-bottom:.5rem; color:#4ade80;"></i>
                    Belum ada keluhan. Semua baik-baik saja!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($complaints->hasPages())
    <div style="padding:1rem 1.25rem; border-top:1px solid rgba(255,255,255,0.07);">
        {{ $complaints->links() }}
    </div>
    @endif
</div>

@endsection