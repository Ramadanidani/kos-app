@extends('layouts.admin')

@section('title', 'Pembayaran')
@section('page-title', 'Pembayaran')

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
    .form-mk-sm:focus {
        outline: none;
        border-color: var(--accent);
        background: transparent;
    }
    .form-mk-sm option { background: var(--bg-card); }
</style>
@endpush

@section('content')

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,0.12);">
                <i class="bi bi-clock-history" style="color:#f87171;"></i>
            </div>
            <div style="font-size:1.6rem; font-weight:700; color:#f87171; line-height:1;">
                Rp {{ number_format($totalUnpaid, 0, ',', '.') }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">
                Total Belum Dibayar
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(34,197,94,0.12);">
                <i class="bi bi-check-circle-fill" style="color:#4ade80;"></i>
            </div>
            <div style="font-size:1.6rem; font-weight:700; color:#4ade80; line-height:1;">
                Rp {{ number_format($totalPaid, 0, ',', '.') }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">
                Total Sudah Dibayar
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(234,179,8,0.12);">
                <i class="bi bi-exclamation-triangle-fill" style="color:#fbbf24;"></i>
            </div>
            <div style="font-size:1.6rem; font-weight:700; color:#fbbf24; line-height:1;">
                {{ $totalOverdue }}
            </div>
            <div style="color:var(--text-muted); font-size:.82rem; margin-top:4px;">
                Tagihan Terlambat
            </div>
        </div>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.payments.index') }}"
          class="d-flex flex-wrap gap-2 align-items-center">
        <select name="status" class="form-mk-sm" onchange="this.form.submit()">
            <option value="all"     {{ request('status', 'all') == 'all'     ? 'selected' : '' }}>Semua Status</option>
            <option value="unpaid"  {{ request('status') == 'unpaid'  ? 'selected' : '' }}>Belum Bayar</option>
            <option value="paid"    {{ request('status') == 'paid'    ? 'selected' : '' }}>Lunas</option>
            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
        </select>
        <input type="month" name="month" class="form-mk-sm"
               value="{{ request('month') }}" onchange="this.form.submit()">
        @if(request('status') || request('month'))
        <a href="{{ route('admin.payments.index') }}"
           style="color:var(--text-muted); font-size:.82rem; text-decoration:none;">
            <i class="bi bi-x-circle"></i> Reset
        </a>
        @endif
    </form>

    <div class="d-flex gap-2">
        {{-- Generate Bulanan --}}
        <button onclick="document.getElementById('generateModal').style.display='flex'"
                style="background:rgba(255,255,255,0.06); color:var(--text-muted);
                       border:1px solid rgba(255,255,255,0.1); border-radius:10px;
                       padding:8px 14px; font-size:.85rem; cursor:pointer;
                       display:flex; align-items:center; gap:6px;">
            <i class="bi bi-calendar-plus"></i> Generate Bulanan
        </button>

        <a href="{{ route('admin.payments.create') }}"
           style="background:var(--accent); color:#fff; text-decoration:none;
                  padding:8px 16px; border-radius:10px; font-size:.85rem;
                  font-weight:600; display:inline-flex; align-items:center; gap:6px;">
            <i class="bi bi-plus-lg"></i> Tambah Tagihan
        </a>
    </div>
</div>

{{-- TABEL --}}
<div class="content-card">
    <table class="table-mk">
        <thead>
            <tr>
                <th>Penghuni</th>
                <th>Kamar</th>
                <th>Jumlah</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Bayar</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td>
                    <div style="font-weight:600; color:var(--text-white);">
                        {{ $payment->tenant->name ?? '—' }}
                    </div>
                </td>
                <td>
                    <span style="font-size:.8rem; padding:3px 10px;
                                 background:rgba(255,140,50,0.1); color:var(--accent);
                                 border-radius:20px; border:1px solid rgba(255,140,50,0.2);">
                        {{ $payment->room->name ?? '—' }}
                    </span>
                </td>
                <td style="color:var(--accent); font-weight:600; font-size:.9rem;">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </td>
                <td style="color:var(--text-muted); font-size:.85rem;">
                    {{ $payment->due_date->format('d M Y') }}
                </td>
                <td style="color:var(--text-muted); font-size:.85rem;">
                    {{ $payment->paid_date?->format('d M Y') ?? '—' }}
                </td>
                <td style="color:var(--text-muted); font-size:.82rem;">
                    {{ $payment->method ?? '—' }}
                </td>

                {{-- Status --}}
                <td>
                    @php
                        $map = [
                            'paid'    => ['Lunas',       'badge-paid'],
                            'unpaid'  => ['Belum Bayar', 'badge-unpaid'],
                            'overdue' => ['Terlambat',   'badge-overdue'],
                        ];
                        [$label, $class] = $map[$payment->status] ?? [$payment->status, ''];
                    @endphp
                    <span class="{{ $class }}"
                          style="font-size:.72rem; padding:3px 9px;
                                 border-radius:20px; font-weight:500;">
                        {{ $label }}
                    </span>
                </td>

                {{-- Aksi --}}
                <td>
                    <div class="d-flex gap-1 flex-wrap">
                        {{-- Konfirmasi (hanya jika belum bayar) --}}
                        @if($payment->status !== 'paid')
                        <form method="POST"
                              action="{{ route('admin.payments.confirm', $payment) }}"
                              onsubmit="return confirm('Konfirmasi pembayaran ini?')">
                            @csrf
                            <button type="submit"
                                    style="padding:5px 10px; background:rgba(34,197,94,0.12);
                                           color:#4ade80; border-radius:7px; font-size:.8rem;
                                           border:1px solid rgba(34,197,94,0.2); cursor:pointer;"
                                    title="Konfirmasi Lunas">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                        {{-- Reminder WA (hanya jika belum bayar) --}}
                        @if($payment->status !== 'paid')
                        <form method="POST"
                            action="{{ route('admin.whatsapp.reminder', $payment) }}"
                            onsubmit="return confirm('Kirim reminder WhatsApp ke {{ $payment->tenant->name }}?')">
                            @csrf
                            <button type="submit"
                                    style="padding:5px 10px; background:rgba(37,211,102,0.1);
                                        color:#25d366; border-radius:7px; font-size:.8rem;
                                        border:1px solid rgba(37,211,102,0.2); cursor:pointer;"
                                    title="Kirim Reminder WA">
                                <i class="bi bi-whatsapp"></i>
                            </button>
                        </form>
                        @endif
                        @endif

                        <a href="{{ route('admin.payments.edit', $payment) }}"
                           style="padding:5px 10px; background:rgba(255,140,50,0.1);
                                  color:var(--accent); border-radius:7px; font-size:.8rem;
                                  text-decoration:none; border:1px solid rgba(255,140,50,0.2);"
                           title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.payments.destroy', $payment) }}"
                              onsubmit="return confirm('Hapus tagihan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="padding:5px 10px; background:rgba(239,68,68,0.1);
                                           color:#f87171; border-radius:7px; font-size:.8rem;
                                           border:1px solid rgba(239,68,68,0.2); cursor:pointer;"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:3rem; color:var(--text-muted);">
                    <i class="bi bi-inbox" style="font-size:2.5rem; display:block; margin-bottom:.5rem;"></i>
                    Belum ada data pembayaran.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($payments->hasPages())
    <div style="padding:1rem 1.25rem; border-top:1px solid rgba(255,255,255,0.07);">
        {{ $payments->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- MODAL Generate Bulanan --}}
<div id="generateModal"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.6);
            z-index:9999; align-items:center; justify-content:center;">
    <div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.1);
                border-radius:18px; padding:2rem; width:100%; max-width:420px; margin:1rem;">

        <h5 style="color:var(--text-white); font-weight:700; margin-bottom:6px;">
            Generate Tagihan Bulanan
        </h5>
        <p style="color:var(--text-muted); font-size:.88rem; margin-bottom:1.25rem;">
            Buat tagihan otomatis untuk semua penghuni aktif berdasarkan harga kamar masing-masing.
        </p>

        <form method="POST" action="{{ route('admin.payments.generate-monthly') }}">
            @csrf
            <label style="color:var(--text-muted); font-size:.82rem; display:block; margin-bottom:6px;">
                Pilih Bulan & Tahun
            </label>
            <input type="month" name="month" class="form-mk-sm"
                   value="{{ date('Y-m') }}"
                   style="width:100%; padding:.7rem 1rem; border-radius:10px;
                          background:transparent; border:1px solid rgba(255,255,255,0.12);
                          color:var(--text-white); font-size:.9rem; margin-bottom:1.25rem;">

            <div class="d-flex gap-2">
                <button type="submit"
                        style="flex:1; background:var(--accent); color:#fff; border:none;
                               border-radius:10px; padding:11px; font-weight:600;
                               cursor:pointer; font-size:.9rem;">
                    <i class="bi bi-calendar-plus me-1"></i> Generate
                </button>
                <button type="button"
                        onclick="document.getElementById('generateModal').style.display='none'"
                        style="flex:1; background:rgba(255,255,255,0.06); color:var(--text-muted);
                               border:1px solid rgba(255,255,255,0.1); border-radius:10px;
                               padding:11px; cursor:pointer; font-size:.9rem;">
                    Batal
                </button>
            </div>
        </form>
        {{-- Bulk Reminder --}}
        <form method="POST" action="{{ route('admin.whatsapp.bulk-reminder') }}"
            onsubmit="return confirm('Kirim reminder ke semua penghuni yang belum bayar?')">
            @csrf
            <button type="submit"
                    style="background:rgba(37,211,102,0.12); color:#25d366;
                        border:1px solid rgba(37,211,102,0.25); border-radius:10px;
                        padding:8px 14px; font-size:.85rem; cursor:pointer;
                        display:flex; align-items:center; gap:6px;">
                <i class="bi bi-whatsapp"></i> Reminder Semua
            </button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Tutup modal klik luar
    document.getElementById('generateModal').addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
</script>
@endpush