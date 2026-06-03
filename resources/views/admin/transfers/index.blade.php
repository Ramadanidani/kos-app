@extends('layouts.admin')

@section('title', 'Pengajuan Pindah Kamar')
@section('page-title', 'Pengajuan Pindah Kamar')

@push('styles')
<style>
    .form-mk-sm {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 8px;
        padding: .45rem .85rem;
        color: var(--text-white);
        font-size: .85rem;
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
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">Menunggu</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(34,197,94,0.12);">
                <i class="bi bi-check-circle-fill" style="color:#4ade80;"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; color:#4ade80; line-height:1;">
                {{ $totalApproved }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">Disetujui</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,0.12);">
                <i class="bi bi-x-circle-fill" style="color:#f87171;"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; color:#f87171; line-height:1;">
                {{ $totalRejected }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">Ditolak</div>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <form method="GET" action="{{ route('admin.transfers.index') }}"
          class="d-flex gap-2 align-items-center">
        <select name="status" class="form-mk-sm" onchange="this.form.submit()">
            <option value="all"     {{ request('status','all') == 'all'     ? 'selected':'' }}>Semua Status</option>
            <option value="pending" {{ request('status') == 'pending'  ? 'selected':'' }}>Menunggu</option>
            <option value="approved"{{ request('status') == 'approved' ? 'selected':'' }}>Disetujui</option>
            <option value="rejected"{{ request('status') == 'rejected' ? 'selected':'' }}>Ditolak</option>
        </select>
        @if(request('status'))
        <a href="{{ route('admin.transfers.index') }}"
           style="color:var(--text-muted); font-size:.82rem; text-decoration:none;">
            <i class="bi bi-x-circle"></i> Reset
        </a>
        @endif
    </form>
    <p style="color:var(--text-muted); margin:0; font-size:.85rem;">
        Total {{ $transfers->total() }} pengajuan
    </p>
</div>

{{-- TABEL --}}
<div class="content-card">
    <table class="table-mk">
        <thead>
            <tr>
                <th>Penghuni</th>
                <th>Dari Kamar</th>
                <th>Ke Kamar</th>
                <th>Alasan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transfers as $transfer)
            <tr>

                {{-- Penghuni --}}
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px; height:32px; background:rgba(255,140,50,0.15);
                                    border-radius:50%; display:flex; align-items:center;
                                    justify-content:center; color:var(--accent);
                                    font-weight:700; font-size:.8rem; flex-shrink:0;">
                            {{ strtoupper(substr($transfer->tenant->name ?? 'T', 0, 1)) }}
                        </div>
                        <span style="color:var(--text-white); font-size:.88rem; font-weight:500;">
                            {{ $transfer->tenant->name ?? '—' }}
                        </span>
                    </div>
                </td>

                {{-- Dari --}}
                <td>
                    <span style="font-size:.8rem; padding:3px 10px;
                                 background:rgba(239,68,68,0.1); color:#f87171;
                                 border-radius:20px; border:1px solid rgba(239,68,68,0.2);">
                        {{ $transfer->fromRoom->name ?? '—' }}
                    </span>
                </td>

                {{-- Ke --}}
                <td>
                    <span style="font-size:.8rem; padding:3px 10px;
                                 background:rgba(34,197,94,0.1); color:#4ade80;
                                 border-radius:20px; border:1px solid rgba(34,197,94,0.2);">
                        {{ $transfer->toRoom->name ?? '—' }}
                    </span>
                </td>

                {{-- Alasan --}}
                <td style="color:var(--text-muted); font-size:.82rem; max-width:180px;">
                    {{ Str::limit($transfer->reason ?? '—', 40) }}
                </td>

                {{-- Tanggal --}}
                <td style="color:var(--text-muted); font-size:.82rem;">
                    {{ $transfer->created_at->format('d M Y') }}
                    <div style="font-size:.72rem;">
                        {{ $transfer->created_at->diffForHumans() }}
                    </div>
                </td>

                {{-- Status --}}
                <td>
                    @php
                        $map = [
                            'pending'  => ['Menunggu',  '#fbbf24', 'rgba(234,179,8,0.15)'],
                            'approved' => ['Disetujui', '#4ade80', 'rgba(34,197,94,0.15)'],
                            'rejected' => ['Ditolak',   '#f87171', 'rgba(239,68,68,0.15)'],
                        ];
                        [$lbl, $color, $bg] = $map[$transfer->status] ?? [$transfer->status, '#fff', 'rgba(255,255,255,0.1)'];
                    @endphp
                    <span style="font-size:.72rem; padding:3px 10px; border-radius:20px;
                                 font-weight:500; color:{{ $color }}; background:{{ $bg }};">
                        {{ $lbl }}
                    </span>
                </td>

                {{-- Aksi --}}
                <td>
                    <div class="d-flex gap-1 flex-wrap">
                        <a href="{{ route('admin.transfers.show', $transfer) }}"
                           style="padding:5px 10px; background:rgba(255,140,50,0.1);
                                  color:var(--accent); border-radius:7px; font-size:.8rem;
                                  text-decoration:none; border:1px solid rgba(255,140,50,0.2);"
                           title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>

                        @if($transfer->status === 'pending')
                        {{-- Approve --}}
                        <form method="POST"
                              action="{{ route('admin.transfers.approve', $transfer) }}"
                              onsubmit="return confirm('Setujui pengajuan ini? Kamar akan otomatis dipindah.')">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    style="padding:5px 10px; background:rgba(34,197,94,0.12);
                                           color:#4ade80; border-radius:7px; font-size:.8rem;
                                           border:1px solid rgba(34,197,94,0.2); cursor:pointer;"
                                    title="Setujui">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>

                        {{-- Reject --}}
                        <button onclick="openRejectModal({{ $transfer->id }})"
                                style="padding:5px 10px; background:rgba(239,68,68,0.1);
                                       color:#f87171; border-radius:7px; font-size:.8rem;
                                       border:1px solid rgba(239,68,68,0.2); cursor:pointer;"
                                title="Tolak">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        @endif

                        <form method="POST"
                              action="{{ route('admin.transfers.destroy', $transfer) }}"
                              onsubmit="return confirm('Hapus pengajuan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="padding:5px 10px; background:rgba(255,255,255,0.06);
                                           color:var(--text-muted); border-radius:7px; font-size:.8rem;
                                           border:1px solid rgba(255,255,255,0.08); cursor:pointer;"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:3rem; color:var(--text-muted);">
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
        {{ $transfers->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- MODAL REJECT --}}
<div id="rejectModal"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.6);
            z-index:9999; align-items:center; justify-content:center;">
    <div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.1);
                border-radius:18px; padding:2rem; width:100%; max-width:440px; margin:1rem;">

        <h5 style="color:var(--text-white); font-weight:700; margin-bottom:6px;">
            <i class="bi bi-x-circle me-2" style="color:#f87171;"></i>
            Tolak Pengajuan
        </h5>
        <p style="color:var(--text-muted); font-size:.88rem; margin-bottom:1.25rem;">
            Berikan alasan penolakan agar penghuni mengetahui penyebabnya.
        </p>

        <form id="rejectForm" method="POST">
            @csrf @method('PATCH')
            <label style="color:var(--text-muted); font-size:.82rem; display:block; margin-bottom:6px;">
                Alasan Penolakan (opsional)
            </label>
            <textarea name="admin_notes" rows="3"
                      placeholder="cth: Kamar tujuan sedang dalam renovasi..."
                      style="background:transparent; border:1px solid rgba(255,255,255,0.12);
                             border-radius:10px; padding:.7rem 1rem; color:var(--text-white);
                             font-size:.9rem; width:100%; resize:vertical; margin-bottom:1.25rem;">
            </textarea>
            <div class="d-flex gap-2">
                <button type="submit"
                        style="flex:1; background:rgba(239,68,68,0.15); color:#f87171;
                               border:1px solid rgba(239,68,68,0.3); border-radius:10px;
                               padding:11px; font-weight:600; cursor:pointer; font-size:.9rem;">
                    <i class="bi bi-x-lg me-1"></i> Tolak Pengajuan
                </button>
                <button type="button" onclick="closeRejectModal()"
                        style="flex:1; background:rgba(255,255,255,0.06); color:var(--text-muted);
                               border:1px solid rgba(255,255,255,0.1); border-radius:10px;
                               padding:11px; cursor:pointer; font-size:.9rem;">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openRejectModal(id) {
        document.getElementById('rejectForm').action =
            `/admin/transfers/${id}/reject`;
        document.getElementById('rejectModal').style.display = 'flex';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }

    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
</script>
@endpush