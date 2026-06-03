@extends('layouts.tenant')

@section('title', $complaint->title)
@section('page-title', 'Detail Keluhan')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('tenant.complaints.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">Detail Keluhan</span>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        {{-- Keluhan --}}
        <div class="content-card mb-3">
            <div class="content-card-header">
                <div>
                    <h5 style="color:var(--text-white); font-weight:700; margin:0 0 6px;">
                        {{ $complaint->title }}
                    </h5>
                    <span style="color:var(--text-muted); font-size:.78rem;">
                        <i class="bi bi-calendar me-1"></i>
                        {{ $complaint->created_at->format('d M Y, H:i') }}
                    </span>
                </div>
                @php
                    $map = [
                        'pending'     => ['Pending',  '#fbbf24', 'rgba(234,179,8,0.15)'],
                        'in_progress' => ['Diproses', '#60a5fa', 'rgba(59,130,246,0.15)'],
                        'resolved'    => ['Selesai',  '#4ade80', 'rgba(34,197,94,0.15)'],
                    ];
                    [$lbl, $color, $bg] = $map[$complaint->status] ?? [$complaint->status, '#fff', 'transparent'];
                @endphp
                <span style="font-size:.78rem; padding:5px 12px; border-radius:20px;
                             font-weight:600; color:{{ $color }}; background:{{ $bg }};
                             white-space:nowrap;">
                    {{ $lbl }}
                </span>
            </div>
            <div class="content-card-body">
                <p style="color:var(--text-muted); line-height:1.8; margin:0; font-size:.92rem;">
                    {{ $complaint->description }}
                </p>
            </div>
        </div>

        {{-- Balasan Admin --}}
        @if($complaint->admin_notes)
        <div class="content-card">
            <div class="content-card-header">
                <h6 style="color:var(--text-white); font-weight:600; margin:0;">
                    <i class="bi bi-shield-check me-2" style="color:var(--accent);"></i>
                    Balasan Admin
                </h6>
                <span style="color:var(--text-muted); font-size:.78rem;">
                    {{ $complaint->updated_at->format('d M Y, H:i') }}
                </span>
            </div>
            <div class="content-card-body">
                <p style="color:var(--text-muted); line-height:1.8; margin:0; font-size:.92rem;">
                    {{ $complaint->admin_notes }}
                </p>
            </div>
        </div>
        @else
        <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07);
                    border-radius:14px; padding:1.5rem; text-align:center;">
            <i class="bi bi-hourglass-split" style="font-size:2rem; color:var(--text-muted);"></i>
            <p style="color:var(--text-muted); margin:.5rem 0 0; font-size:.85rem;">
                Menunggu respons dari admin.
            </p>
        </div>
        @endif

    </div>

    {{-- Tracking Status --}}
    <div class="col-lg-4">
        <div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.07);
                    border-radius:14px; padding:1.25rem;">
            <h6 style="color:var(--text-white); font-weight:600; margin-bottom:1.25rem;">
                <i class="bi bi-diagram-3 me-2" style="color:var(--accent);"></i>
                Tracking Status
            </h6>

            @php
                $steps = [
                    ['pending',     'Pending',   'Keluhan diterima admin',          'bi-hourglass-split', '#fbbf24'],
                    ['in_progress', 'Diproses',  'Admin sedang menangani keluhan',  'bi-arrow-repeat',    '#60a5fa'],
                    ['resolved',    'Selesai',   'Keluhan telah diselesaikan',      'bi-check-circle',    '#4ade80'],
                ];
                $statusOrder = ['pending' => 0, 'in_progress' => 1, 'resolved' => 2];
                $currentOrder = $statusOrder[$complaint->status] ?? 0;
            @endphp

            @foreach($steps as $i => [$val, $label, $desc, $icon, $color])
            @php $isDone = $i <= $currentOrder; @endphp
            <div style="display:flex; gap:14px; margin-bottom:{{ $i < count($steps)-1 ? '1rem' : '0' }};">
                <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0;">
                    <div style="width:32px; height:32px; border-radius:50%;
                                background:{{ $isDone ? $color : 'rgba(255,255,255,0.06)' }};
                                display:flex; align-items:center; justify-content:center;
                                border:2px solid {{ $isDone ? $color : 'rgba(255,255,255,0.1)' }};">
                        <i class="bi {{ $icon }}"
                           style="font-size:.8rem; color:{{ $isDone ? '#fff' : 'rgba(255,255,255,0.3)' }};"></i>
                    </div>
                    @if($i < count($steps)-1)
                    <div style="width:2px; flex:1; min-height:24px; margin:4px 0;
                                background:{{ $isDone && $i < $currentOrder ? $color : 'rgba(255,255,255,0.08)' }};"></div>
                    @endif
                </div>
                <div style="padding-top:4px;">
                    <div style="color:{{ $isDone ? 'var(--text-white)' : 'rgba(255,255,255,0.3)' }};
                                font-weight:600; font-size:.85rem;">
                        {{ $label }}
                    </div>
                    <div style="color:{{ $isDone ? 'var(--text-muted)' : 'rgba(255,255,255,0.2)' }};
                                font-size:.75rem; margin-top:2px;">
                        {{ $desc }}
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>

@endsection