@extends('layouts.admin')

@section('title', 'Laporan Pembayaran')
@section('page-title', 'Laporan Pembayaran')

@push('styles')
<style>
    .form-mk-sm { background:transparent; border:1px solid rgba(255,255,255,0.12); border-radius:8px; padding:.45rem .85rem; color:var(--text-white); font-size:.85rem; }
    .form-mk-sm:focus { outline:none; border-color:var(--accent); background:transparent; }
    .form-mk-sm option { background:var(--bg-card); }
</style>
@endpush

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(234,179,8,0.12);">
                <i class="bi bi-hourglass-split" style="color:#fbbf24;"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; color:#fbbf24; line-height:1;">
                {{ $totalPending }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">
                Menunggu Verifikasi
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(34,197,94,0.12);">
                <i class="bi bi-check-circle-fill" style="color:#4ade80;"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; color:#4ade80; line-height:1;">
                {{ $totalVerified }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">
                Sudah Diverifikasi
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,0.12);">
                <i class="bi bi-x-circle-fill" style="color:#f87171;"></i>
            </div>
            <div style="font-size:1.8rem; font-weight:700; color:#f87171; line-height:1;">
                {{ $totalRejected ?? 0 }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">
                Ditolak
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" action="{{ route('admin.payment-reports.index') }}"
          class="d-flex flex-wrap gap-2 align-items-center">

        <select name="status" class="form-mk-sm" onchange="this.form.submit()">
            <option value="all"      {{ request('status','all') == 'all'      ? 'selected':'' }}>Semua Status</option>
            <option value="pending"  {{ request('status') == 'pending'  ? 'selected':'' }}>Menunggu</option>
            <option value="verified" {{ request('status') == 'verified' ? 'selected':'' }}>Terverifikasi</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected':'' }}>Ditolak</option>
        </select>

        <select name="period" class="form-mk-sm" onchange="this.form.submit()">
            <option value="">Semua Periode</option>
            @foreach($periods as $period)
            <option value="{{ $period }}" {{ request('period') == $period ? 'selected':'' }}>
                {{ \Carbon\Carbon::parse($period . '-01')->translatedFormat('F Y') }}
            </option>
            @endforeach
        </select>

        @if(request('status') || request('period'))
        <a href="{{ route('admin.payment-reports.index') }}"
           style="color:var(--text-muted); font-size:.82rem; text-decoration:none;">
            <i class="bi bi-x-circle"></i> Reset
        </a>
        @endif
    </form>

    <p style="color:var(--text-muted); margin:0; font-size:.85rem;">
        Total {{ $reports->total() }} laporan
    </p>
</div>

{{-- Tabel --}}
<div class="content-card">
    <table class="table-mk">
        <thead>
            <tr>
                <th>Penghuni</th>
                <th>Kamar</th>
                <th>Periode</th>
                <th>Jumlah</th>
                <th>Metode</th>
                <th>Bukti</th>
                <th>Dikirim</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
            <tr>
                {{-- Penghuni --}}
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:32px; height:32px; background:rgba(255,140,50,0.15);
                                    border-radius:50%; display:flex; align-items:center;
                                    justify-content:center; color:var(--accent);
                                    font-weight:700; font-size:.8rem; flex-shrink:0;">
                            {{ strtoupper(substr($report->tenant->name ?? 'T', 0, 1)) }}
                        </div>
                        <span style="color:var(--text-white); font-size:.88rem; font-weight:500;">
                            {{ $report->tenant->name ?? '—' }}
                        </span>
                    </div>
                </td>

                {{-- Kamar --}}
                <td>
                    <span style="font-size:.8rem; padding:3px 10px;
                                 background:rgba(255,140,50,0.1); color:var(--accent);
                                 border-radius:20px; border:1px solid rgba(255,140,50,0.2);">
                        {{ $report->room->name ?? '—' }}
                    </span>
                </td>

                {{-- Periode --}}
                <td style="color:var(--text-white); font-weight:500; font-size:.88rem;">
                    {{ \Carbon\Carbon::parse($report->period . '-01')->translatedFormat('F Y') }}
                </td>

                {{-- Jumlah --}}
                <td style="color:var(--accent); font-weight:600; font-size:.88rem;">
                    Rp {{ number_format($report->amount, 0, ',', '.') }}
                </td>

                {{-- Metode --}}
                <td style="color:var(--text-muted); font-size:.82rem;">
                    {{ $report->method }}
                </td>

                {{-- Bukti (thumbnail) --}}
                <td>
                    <a href="{{ asset('storage/' . $report->proof_image) }}"
                       target="_blank"
                       style="display:block; width:48px; height:38px; border-radius:6px;
                              overflow:hidden; border:1px solid rgba(255,255,255,0.1);">
                        <img src="{{ asset('storage/' . $report->proof_image) }}"
                             style="width:100%; height:100%; object-fit:cover;"
                             alt="Bukti">
                    </a>
                </td>

                {{-- Tanggal --}}
                <td style="color:var(--text-muted); font-size:.82rem;">
                    {{ $report->created_at->format('d M Y') }}
                    <div style="font-size:.72rem;">
                        {{ $report->created_at->diffForHumans() }}
                    </div>
                </td>

                {{-- Status --}}
                <td>
                    @php
                        $statusMap = [
                            'verified' => ['Terverifikasi', '#4ade80', 'rgba(34,197,94,0.15)'],
                            'rejected' => ['Ditolak',        '#f87171', 'rgba(239,68,68,0.15)'],
                            'pending'  => ['Menunggu',       '#fbbf24', 'rgba(234,179,8,0.15)'],
                        ];
                        [$sLabel, $sColor, $sBg] = $statusMap[$report->status] ?? $statusMap['pending'];
                    @endphp
                    <span style="background:{{ $sBg }}; color:{{ $sColor }};
                                 font-size:.72rem; padding:3px 10px;
                                 border-radius:20px; font-weight:500;">
                        {{ $sLabel }}
                    </span>
                    @if($report->status === 'rejected' && $report->rejection_reason)
                    <div style="font-size:.68rem; color:var(--text-muted); margin-top:4px; max-width:140px;">
                        <i class="bi bi-info-circle me-1"></i>{{ Str::limit($report->rejection_reason, 35) }}
                    </div>
                    @endif
                </td>

                {{-- Aksi --}}
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.payment-reports.show', $report) }}"
                           style="padding:5px 10px; background:rgba(255,140,50,0.1);
                                  color:var(--accent); border-radius:7px; font-size:.8rem;
                                  text-decoration:none; border:1px solid rgba(255,140,50,0.2);"
                           title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>

                        @if($report->status === 'pending')
                        {{-- Verifikasi --}}
                        <form method="POST"
                              action="{{ route('admin.payment-reports.verify', $report) }}"
                              onsubmit="return confirm('Verifikasi laporan ini?')">
                            @csrf
                            <button type="submit"
                                    style="padding:5px 10px; background:rgba(34,197,94,0.12);
                                           color:#4ade80; border-radius:7px; font-size:.8rem;
                                           border:1px solid rgba(34,197,94,0.2); cursor:pointer;"
                                    title="Verifikasi">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>

                        {{-- Tolak --}}
                        <button type="button"
                                onclick="openRejectModal({{ $report->id }}, '{{ $report->tenant->name ?? '' }}')"
                                style="padding:5px 10px; background:rgba(239,68,68,0.1);
                                       color:#f87171; border-radius:7px; font-size:.8rem;
                                       border:1px solid rgba(239,68,68,0.2); cursor:pointer;"
                                title="Tolak">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        @endif
                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center; padding:3rem; color:var(--text-muted);">
                    <i class="bi bi-file-earmark-x"
                       style="font-size:2.5rem; display:block; margin-bottom:.5rem;"></i>
                    Belum ada laporan pembayaran.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($reports->hasPages())
    <div style="padding:1rem 1.25rem; border-top:1px solid rgba(255,255,255,0.07);">
        {{ $reports->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- ── MODAL TOLAK LAPORAN ── --}}
<div id="rejectModal"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.6);
            z-index:9999; align-items:center; justify-content:center;">
    <div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.1);
                border-radius:18px; padding:2rem; width:100%; max-width:440px; margin:1rem;">

        <h5 style="color:var(--text-white); font-weight:700; margin-bottom:6px;">
            <i class="bi bi-x-circle me-2" style="color:#f87171;"></i>
            Tolak Laporan Pembayaran
        </h5>
        <p id="rejectModalTenantName"
           style="color:var(--text-muted); font-size:.88rem; margin-bottom:1.25rem;">
        </p>

        <form id="rejectForm" method="POST">
            @csrf
            <label style="color:var(--text-muted); font-size:.82rem; display:block; margin-bottom:6px;">
                Alasan Penolakan <span style="color:#f87171;">*</span>
            </label>
            <textarea name="rejection_reason" rows="3" required
                      placeholder="cth: Bukti transfer tidak sesuai dengan mutasi rekening, atau gambar buram/tidak terbaca..."
                      style="background:transparent; border:1px solid rgba(255,255,255,0.12);
                             border-radius:10px; padding:.7rem 1rem; color:var(--text-white);
                             font-size:.9rem; width:100%; resize:vertical; margin-bottom:1.25rem;"></textarea>

            <div style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.15);
                        border-radius:10px; padding:.75rem 1rem; margin-bottom:1.25rem;
                        font-size:.8rem; color:var(--text-muted);">
                <i class="bi bi-exclamation-triangle me-1" style="color:#f87171;"></i>
                Penghuni akan melihat alasan ini di halaman laporan mereka.
            </div>

            <div class="d-flex gap-2">
                <button type="submit"
                        style="flex:1; background:rgba(239,68,68,0.15); color:#f87171;
                               border:1px solid rgba(239,68,68,0.3); border-radius:10px;
                               padding:11px; font-weight:600; cursor:pointer; font-size:.9rem;">
                    <i class="bi bi-x-lg me-1"></i> Tolak Laporan
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
    function openRejectModal(id, tenantName) {
        document.getElementById('rejectForm').action = `/admin/payment-reports/${id}/reject`;
        document.getElementById('rejectModalTenantName').textContent =
            `Berikan alasan penolakan untuk laporan dari ${tenantName}.`;
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