@extends('layouts.admin')

@section('title', 'Detail Pengajuan')
@section('page-title', 'Detail Pengajuan Pindah')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.transfers.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">Detail Pengajuan</span>
</div>

<div class="row g-4">

    {{-- KIRI: Info Pengajuan --}}
    <div class="col-lg-7">
        <div class="content-card mb-3">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-arrow-left-right me-2" style="color:var(--accent);"></i>
                    Detail Perpindahan
                </h6>
                @php
                    $map = [
                        'pending'  => ['Menunggu',  '#fbbf24', 'rgba(234,179,8,0.15)'],
                        'approved' => ['Disetujui', '#4ade80', 'rgba(34,197,94,0.15)'],
                        'rejected' => ['Ditolak',   '#f87171', 'rgba(239,68,68,0.15)'],
                    ];
                    [$lbl, $color, $bg] = $map[$transfer->status] ?? [$transfer->status, '#fff', 'transparent'];
                @endphp
                <span style="font-size:.78rem; padding:5px 12px; border-radius:20px;
                             font-weight:600; color:{{ $color }}; background:{{ $bg }};">
                    {{ $lbl }}
                </span>
            </div>
            <div class="content-card-body">

                {{-- Visualisasi perpindahan --}}
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:1.5rem; flex-wrap:wrap;">

                    {{-- Kamar asal --}}
                    <div style="flex:1; min-width:120px; background:rgba(239,68,68,0.08);
                                border:1px solid rgba(239,68,68,0.15); border-radius:12px;
                                padding:1rem; text-align:center;">
                        <i class="bi bi-door-closed" style="font-size:1.5rem; color:#f87171;"></i>
                        <div style="color:var(--text-muted); font-size:.72rem; margin-top:6px;">Kamar Asal</div>
                        <div style="color:#f87171; font-weight:700; font-size:1rem; margin-top:3px;">
                            {{ $transfer->fromRoom->name ?? '—' }}
                        </div>
                        @if($transfer->fromRoom)
                        <div style="color:var(--text-muted); font-size:.72rem; margin-top:3px;">
                            {{ $transfer->fromRoom->type }} · Lantai {{ $transfer->fromRoom->floor }}
                        </div>
                        @endif
                    </div>

                    {{-- Arrow --}}
                    <div style="text-align:center; flex-shrink:0;">
                        <i class="bi bi-arrow-right-circle-fill"
                           style="font-size:1.8rem; color:var(--accent);"></i>
                    </div>

                    {{-- Kamar tujuan --}}
                    <div style="flex:1; min-width:120px; background:rgba(34,197,94,0.08);
                                border:1px solid rgba(34,197,94,0.15); border-radius:12px;
                                padding:1rem; text-align:center;">
                        <i class="bi bi-door-open" style="font-size:1.5rem; color:#4ade80;"></i>
                        <div style="color:var(--text-muted); font-size:.72rem; margin-top:6px;">Kamar Tujuan</div>
                        <div style="color:#4ade80; font-weight:700; font-size:1rem; margin-top:3px;">
                            {{ $transfer->toRoom->name ?? '—' }}
                        </div>
                        @if($transfer->toRoom)
                        <div style="color:var(--text-muted); font-size:.72rem; margin-top:3px;">
                            {{ $transfer->toRoom->type }} · Lantai {{ $transfer->toRoom->floor }}
                        </div>
                        @endif
                    </div>

                </div>

                {{-- Alasan --}}
                <div style="margin-bottom:1rem;">
                    <div style="color:var(--text-muted); font-size:.78rem; margin-bottom:6px;">
                        Alasan Pengajuan
                    </div>
                    <div style="background:rgba(255,255,255,0.04); border-radius:10px;
                                padding:.85rem 1rem; color:var(--text-white);
                                font-size:.9rem; line-height:1.7;">
                        {{ $transfer->reason ?? 'Tidak ada alasan yang diberikan.' }}
                    </div>
                </div>

                {{-- Catatan admin (jika ada) --}}
                @if($transfer->admin_notes)
                <div>
                    <div style="color:var(--text-muted); font-size:.78rem; margin-bottom:6px;">
                        Catatan Admin
                    </div>
                    <div style="background:rgba(255,140,50,0.08); border:1px solid rgba(255,140,50,0.15);
                                border-radius:10px; padding:.85rem 1rem; color:var(--text-muted);
                                font-size:.88rem; line-height:1.7;">
                        {{ $transfer->admin_notes }}
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- KANAN: Aksi + Info --}}
    <div class="col-lg-5">

        {{-- Aksi (hanya jika pending) --}}
        @if($transfer->status === 'pending')
        <div class="content-card mb-3">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-shield-check me-2" style="color:var(--accent);"></i>
                    Tindakan Admin
                </h6>
            </div>
            <div class="content-card-body">

                {{-- Info kamar tujuan --}}
                @if($transfer->toRoom)
                <div style="background:{{ $transfer->toRoom->status === 'available' ? 'rgba(34,197,94,0.08)' : 'rgba(239,68,68,0.08)' }};
                            border:1px solid {{ $transfer->toRoom->status === 'available' ? 'rgba(34,197,94,0.2)' : 'rgba(239,68,68,0.2)' }};
                            border-radius:10px; padding:.85rem; margin-bottom:1rem; font-size:.82rem;">
                    <i class="bi bi-info-circle me-1"
                       style="color:{{ $transfer->toRoom->status === 'available' ? '#4ade80' : '#f87171' }};"></i>
                    Kamar tujuan saat ini:
                    <strong style="color:{{ $transfer->toRoom->status === 'available' ? '#4ade80' : '#f87171' }};">
                        {{ $transfer->toRoom->status === 'available' ? 'Tersedia ✓' : 'Tidak Tersedia ✗' }}
                    </strong>
                </div>
                @endif

                {{-- Tombol Approve --}}
                <form method="POST"
                      action="{{ route('admin.transfers.approve', $transfer) }}"
                      onsubmit="return confirm('Setujui pengajuan ini? Kamar akan otomatis dipindah.')"
                      style="margin-bottom:.75rem;">
                    @csrf @method('PATCH')
                    <button type="submit"
                            style="width:100%; background:rgba(34,197,94,0.15); color:#4ade80;
                                   border:1px solid rgba(34,197,94,0.3); border-radius:10px;
                                   padding:12px; font-weight:600; cursor:pointer; font-size:.9rem;
                                   display:flex; align-items:center; justify-content:center; gap:8px;"
                            onmouseover="this.style.background='rgba(34,197,94,0.25)'"
                            onmouseout="this.style.background='rgba(34,197,94,0.15)'">
                        <i class="bi bi-check-circle-fill"></i> Setujui Pengajuan
                    </button>
                </form>

                {{-- Form Reject --}}
                <form method="POST" action="{{ route('admin.transfers.reject', $transfer) }}">
                    @csrf @method('PATCH')
                    <label style="color:var(--text-muted); font-size:.78rem; display:block; margin-bottom:6px;">
                        Alasan Penolakan (opsional)
                    </label>
                    <textarea name="admin_notes" rows="2"
                              placeholder="cth: Kamar tujuan sedang renovasi..."
                              style="background:transparent; border:1px solid rgba(255,255,255,0.12);
                                     border-radius:10px; padding:.6rem 1rem; color:var(--text-white);
                                     font-size:.85rem; width:100%; resize:vertical; margin-bottom:.75rem;"></textarea>
                    <button type="submit"
                            style="width:100%; background:rgba(239,68,68,0.12); color:#f87171;
                                   border:1px solid rgba(239,68,68,0.25); border-radius:10px;
                                   padding:11px; font-weight:600; cursor:pointer; font-size:.9rem;
                                   display:flex; align-items:center; justify-content:center; gap:8px;"
                            onmouseover="this.style.background='rgba(239,68,68,0.22)'"
                            onmouseout="this.style.background='rgba(239,68,68,0.12)'"
                            onclick="return confirm('Tolak pengajuan ini?')">
                        <i class="bi bi-x-circle-fill"></i> Tolak Pengajuan
                    </button>
                </form>

            </div>
        </div>
        @endif

        {{-- Info Penghuni --}}
        <div class="content-card">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-person-circle me-2" style="color:var(--accent);"></i>
                    Info Penghuni
                </h6>
            </div>
            <div class="content-card-body">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:1rem;">
                    <div style="width:44px; height:44px; background:rgba(255,140,50,0.15);
                                border-radius:50%; display:flex; align-items:center;
                                justify-content:center; color:var(--accent);
                                font-size:1.1rem; font-weight:700; flex-shrink:0;">
                        {{ strtoupper(substr($transfer->tenant->name ?? 'T', 0, 1)) }}
                    </div>
                    <div>
                        <div style="color:var(--text-white); font-weight:600; font-size:.92rem;">
                            {{ $transfer->tenant->name ?? '—' }}
                        </div>
                        <div style="color:var(--text-muted); font-size:.78rem;">
                            <i class="bi bi-phone me-1"></i>
                            {{ $transfer->tenant->phone ?? '—' }}
                        </div>
                    </div>
                </div>
                <div style="font-size:.82rem; color:var(--text-muted); line-height:2;">
                    <div>
                        <i class="bi bi-clock me-2" style="color:var(--accent);"></i>
                        Diajukan: <strong style="color:var(--text-white);">
                            {{ $transfer->created_at->format('d M Y, H:i') }}
                        </strong>
                    </div>
                    <div>
                        <i class="bi bi-arrow-clockwise me-2" style="color:var(--accent);"></i>
                        Diupdate: <strong style="color:var(--text-white);">
                            {{ $transfer->updated_at->diffForHumans() }}
                        </strong>
                    </div>
                </div>
                @if($transfer->tenant)
                <div style="margin-top:1rem;">
                    <a href="{{ route('admin.tenants.show', $transfer->tenant) }}"
                       style="display:block; text-align:center; padding:8px;
                              border:1px solid rgba(255,140,50,0.3); color:var(--accent);
                              border-radius:8px; font-size:.82rem; text-decoration:none;
                              transition:background .2s;"
                       onmouseover="this.style.background='rgba(255,140,50,0.1)'"
                       onmouseout="this.style.background='transparent'">
                        <i class="bi bi-person me-1"></i> Lihat Profil Penghuni
                    </a>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection