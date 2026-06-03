@extends('layouts.admin')

@section('title', 'Keluhan')
@section('page-title', 'Manajemen Keluhan')

@push('styles')
<style>
    .form-mk-sm {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 8px;
        padding: .45rem .85rem;
        color: var(--text-white);
        font-size: .85rem;
        transition: border-color .2s;
    }
    .form-mk-sm:focus { outline:none; border-color:var(--accent); background:transparent; }
    .form-mk-sm option { background: var(--bg-card); }
</style>
@endpush

@section('content')

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(234,179,8,0.12);">
                <i class="bi bi-hourglass-split" style="color:#fbbf24;"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; color:#fbbf24; line-height:1;">
                {{ $totalPending }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">Pending</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(59,130,246,0.12);">
                <i class="bi bi-arrow-repeat" style="color:#60a5fa;"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; color:#60a5fa; line-height:1;">
                {{ $totalProgress }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">Diproses</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(34,197,94,0.12);">
                <i class="bi bi-check-circle-fill" style="color:#4ade80;"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; color:#4ade80; line-height:1;">
                {{ $totalResolved }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">Selesai</div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <form method="GET" action="{{ route('admin.complaints.index') }}"
          class="d-flex gap-2 align-items-center">
        <select name="status" class="form-mk-sm" onchange="this.form.submit()">
            <option value="all"        {{ request('status','all') == 'all'        ? 'selected':'' }}>Semua Status</option>
            <option value="pending"    {{ request('status') == 'pending'    ? 'selected':'' }}>Pending</option>
            <option value="in_progress"{{ request('status') == 'in_progress'? 'selected':'' }}>Diproses</option>
            <option value="resolved"   {{ request('status') == 'resolved'   ? 'selected':'' }}>Selesai</option>
        </select>
        @if(request('status'))
        <a href="{{ route('admin.complaints.index') }}"
           style="color:var(--text-muted); font-size:.82rem; text-decoration:none;">
            <i class="bi bi-x-circle"></i> Reset
        </a>
        @endif
    </form>
    <p style="color:var(--text-muted); margin:0; font-size:.85rem;">
        Total {{ $complaints->total() }} keluhan
    </p>
</div>

{{-- TABEL --}}
<div class="content-card">
    <table class="table-mk">
        <thead>
            <tr>
                <th>Penghuni</th>
                <th>Kamar</th>
                <th>Judul Keluhan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $complaint)
            <tr>

                {{-- Penghuni --}}
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px; height:32px; background:rgba(255,140,50,0.15);
                                    border-radius:50%; display:flex; align-items:center;
                                    justify-content:center; color:var(--accent);
                                    font-weight:700; font-size:.8rem; flex-shrink:0;">
                            {{ strtoupper(substr($complaint->tenant->name ?? 'T', 0, 1)) }}
                        </div>
                        <span style="color:var(--text-white); font-size:.88rem; font-weight:500;">
                            {{ $complaint->tenant->name ?? '—' }}
                        </span>
                    </div>
                </td>

                {{-- Kamar --}}
                <td>
                    <span style="font-size:.8rem; padding:3px 10px;
                                 background:rgba(255,140,50,0.1); color:var(--accent);
                                 border-radius:20px; border:1px solid rgba(255,140,50,0.2);">
                        {{ $complaint->room->name ?? '—' }}
                    </span>
                </td>

                {{-- Judul --}}
                <td>
                    <div style="color:var(--text-white); font-size:.88rem; font-weight:500;">
                        {{ $complaint->title }}
                    </div>
                    <div style="color:var(--text-muted); font-size:.75rem; margin-top:2px;">
                        {{ Str::limit($complaint->description, 50) }}
                    </div>
                </td>

                {{-- Tanggal --}}
                <td style="color:var(--text-muted); font-size:.85rem;">
                    {{ $complaint->created_at->format('d M Y') }}
                    <div style="font-size:.72rem;">
                        {{ $complaint->created_at->diffForHumans() }}
                    </div>
                </td>

                {{-- Status --}}
                <td>
                    @php
                        $map = [
                            'pending'     => ['Pending',   'badge-pending'],
                            'in_progress' => ['Diproses',  'badge-in_progress'],
                            'resolved'    => ['Selesai',   'badge-resolved'],
                        ];
                        [$label, $class] = $map[$complaint->status] ?? [$complaint->status, ''];
                    @endphp
                    <span class="{{ $class }}"
                          style="font-size:.72rem; padding:3px 10px;
                                 border-radius:20px; font-weight:500;">
                        {{ $label }}
                    </span>
                </td>

                {{-- Aksi --}}
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.complaints.show', $complaint) }}"
                           style="padding:5px 12px; background:rgba(255,140,50,0.1);
                                  color:var(--accent); border-radius:7px; font-size:.8rem;
                                  text-decoration:none; border:1px solid rgba(255,140,50,0.2);">
                            <i class="bi bi-eye me-1"></i> Detail
                        </a>
                        <form method="POST"
                              action="{{ route('admin.complaints.destroy', $complaint) }}"
                              onsubmit="return confirm('Hapus keluhan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="padding:5px 10px; background:rgba(239,68,68,0.1);
                                           color:#f87171; border-radius:7px; font-size:.8rem;
                                           border:1px solid rgba(239,68,68,0.2); cursor:pointer;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:3rem; color:var(--text-muted);">
                    <i class="bi bi-chat-square-text"
                       style="font-size:2.5rem; display:block; margin-bottom:.5rem;"></i>
                    Belum ada keluhan masuk.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($complaints->hasPages())
    <div style="padding:1rem 1.25rem; border-top:1px solid rgba(255,255,255,0.07);">
        {{ $complaints->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection