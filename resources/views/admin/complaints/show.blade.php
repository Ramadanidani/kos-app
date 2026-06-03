@extends('layouts.admin')

@section('title', $complaint->title)
@section('page-title', 'Detail Keluhan')

@push('styles')
<style>
    .form-mk { background:transparent; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:.7rem 1rem; color:var(--text-white); font-size:.9rem; width:100%; transition:border-color .2s; }
    .form-mk:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(255,140,50,0.15); background:transparent; }
    .form-mk option { background:var(--bg-card); }
    .label-mk { color:var(--text-muted); font-size:.82rem; margin-bottom:6px; display:block; }
    .timeline-item { position:relative; padding-left:28px; padding-bottom:1.25rem; }
    .timeline-item::before { content:''; position:absolute; left:8px; top:20px; bottom:0; width:1px; background:rgba(255,255,255,0.08); }
    .timeline-item:last-child::before { display:none; }
    .timeline-dot { position:absolute; left:0; top:4px; width:18px; height:18px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.6rem; }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.complaints.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">Detail Keluhan</span>
</div>

<div class="row g-4">

    {{-- KIRI: Detail Keluhan --}}
    <div class="col-lg-7">

        {{-- Header keluhan --}}
        <div class="content-card mb-3">
            <div class="content-card-header">
                <div>
                    <h5 style="color:var(--text-white); font-weight:700; margin:0 0 6px;">
                        {{ $complaint->title }}
                    </h5>
                    <div style="display:flex; gap:12px; flex-wrap:wrap; font-size:.8rem; color:var(--text-muted);">
                        <span>
                            <i class="bi bi-person me-1"></i>
                            {{ $complaint->tenant->name ?? '—' }}
                        </span>
                        <span>
                            <i class="bi bi-door-open me-1"></i>
                            {{ $complaint->room->name ?? '—' }}
                        </span>
                        <span>
                            <i class="bi bi-calendar me-1"></i>
                            {{ $complaint->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
                @php
                    $map = [
                        'pending'     => ['Pending',  'badge-pending'],
                        'in_progress' => ['Diproses', 'badge-in_progress'],
                        'resolved'    => ['Selesai',  'badge-resolved'],
                    ];
                    [$label, $class] = $map[$complaint->status] ?? [$complaint->status, ''];
                @endphp
                <span class="{{ $class }}"
                      style="font-size:.78rem; padding:5px 12px;
                             border-radius:20px; font-weight:600; white-space:nowrap;">
                    {{ $label }}
                </span>
            </div>
            <div class="content-card-body">
                <p style="color:var(--text-muted); line-height:1.8; margin:0; font-size:.92rem;">
                    {{ $complaint->description }}
                </p>
            </div>
        </div>

        {{-- Catatan admin --}}
        @if($complaint->admin_notes)
        <div class="content-card">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-shield-check me-2" style="color:var(--accent);"></i>
                    Catatan Admin
                </h6>
            </div>
            <div class="content-card-body">
                <p style="color:var(--text-muted); line-height:1.8; margin:0; font-size:.9rem;">
                    {{ $complaint->admin_notes }}
                </p>
            </div>
        </div>
        @endif

    </div>

    {{-- KANAN: Update Status + Info --}}
    <div class="col-lg-5">

        {{-- Update Status --}}
        <div class="content-card mb-3">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-pencil-square me-2" style="color:var(--accent);"></i>
                    Update Status
                </h6>
            </div>
            <div class="content-card-body">
                <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
                    @csrf @method('PUT')

                    <label class="label-mk">Status Keluhan</label>
                    <div class="d-flex flex-column gap-2 mb-3">

                        @foreach([
                            ['pending',     'Pending',   'badge-pending',     'bi-hourglass-split', '#fbbf24'],
                            ['in_progress', 'Diproses',  'badge-in_progress', 'bi-arrow-repeat',    '#60a5fa'],
                            ['resolved',    'Selesai',   'badge-resolved',    'bi-check-circle',    '#4ade80'],
                        ] as [$val, $lbl, $badgeClass, $icon, $color])

                        <label style="display:flex; align-items:center; gap:12px;
                                      padding:10px 14px; border-radius:10px; cursor:pointer;
                                      border:1px solid {{ $complaint->status === $val ? $color : 'rgba(255,255,255,0.07)' }};
                                      background:{{ $complaint->status === $val ? 'rgba(255,255,255,0.04)' : 'transparent' }};
                                      transition:.2s;"
                               onmouseover="this.style.borderColor='{{ $color }}'"
                               onmouseout="this.style.borderColor='{{ $complaint->status === $val ? $color : 'rgba(255,255,255,0.07)' }}'">
                            <input type="radio" name="status" value="{{ $val }}"
                                   {{ $complaint->status === $val ? 'checked' : '' }}
                                   style="accent-color:{{ $color }};">
                            <i class="bi {{ $icon }}" style="color:{{ $color }};"></i>
                            <span style="color:var(--text-white); font-size:.88rem;">{{ $lbl }}</span>
                        </label>

                        @endforeach
                    </div>

                    <label class="label-mk">Catatan Admin</label>
                    <textarea name="admin_notes" class="form-mk" rows="3"
                              placeholder="Tulis tindakan atau catatan penanganan...">{{ old('admin_notes', $complaint->admin_notes) }}</textarea>

                    <button type="submit"
                            style="width:100%; margin-top:1rem; background:var(--accent);
                                   color:#fff; border:none; border-radius:10px; padding:11px;
                                   font-weight:600; cursor:pointer; font-size:.9rem;
                                   display:flex; align-items:center; justify-content:center; gap:8px;"
                            onmouseover="this.style.opacity='.85'"
                            onmouseout="this.style.opacity='1'">
                        <i class="bi bi-check-lg"></i> Simpan Update
                    </button>
                </form>
            </div>
        </div>

        {{-- Info Penghuni --}}
        <div class="content-card">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-person-circle me-2" style="color:var(--accent);"></i>
                    Info Pelapor
                </h6>
            </div>
            <div class="content-card-body">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:1rem;">
                    <div style="width:44px; height:44px; background:rgba(255,140,50,0.15);
                                border-radius:50%; display:flex; align-items:center;
                                justify-content:center; color:var(--accent);
                                font-size:1.1rem; font-weight:700; flex-shrink:0;">
                        {{ strtoupper(substr($complaint->tenant->name ?? 'T', 0, 1)) }}
                    </div>
                    <div>
                        <div style="color:var(--text-white); font-weight:600; font-size:.92rem;">
                            {{ $complaint->tenant->name ?? '—' }}
                        </div>
                        <div style="color:var(--text-muted); font-size:.78rem;">
                            <i class="bi bi-phone me-1"></i>
                            {{ $complaint->tenant->phone ?? '—' }}
                        </div>
                    </div>
                </div>

                <div style="font-size:.82rem; color:var(--text-muted); line-height:2;">
                    <div>
                        <i class="bi bi-door-open me-2" style="color:var(--accent);"></i>
                        Kamar: <strong style="color:var(--text-white);">
                            {{ $complaint->room->name ?? '—' }}
                        </strong>
                    </div>
                    <div>
                        <i class="bi bi-clock me-2" style="color:var(--accent);"></i>
                        Dilaporkan: <strong style="color:var(--text-white);">
                            {{ $complaint->created_at->diffForHumans() }}
                        </strong>
                    </div>
                    @if($complaint->updated_at != $complaint->created_at)
                    <div>
                        <i class="bi bi-arrow-clockwise me-2" style="color:var(--accent);"></i>
                        Diupdate: <strong style="color:var(--text-white);">
                            {{ $complaint->updated_at->diffForHumans() }}
                        </strong>
                    </div>
                    @endif
                </div>

                @if($complaint->tenant)
                <div style="margin-top:1rem;">
                    <a href="{{ route('admin.tenants.show', $complaint->tenant) }}"
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