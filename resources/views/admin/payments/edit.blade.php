@extends('layouts.admin')

@section('title', 'Edit Tagihan')
@section('page-title', 'Edit Tagihan')

@push('styles')
<style>
    .form-mk { background:transparent; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:.7rem 1rem; color:var(--text-white); font-size:.9rem; width:100%; transition:border-color .2s; }
    .form-mk:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(255,140,50,0.15); background:transparent; }
    .form-mk option { background:var(--bg-card); }
    .label-mk { color:var(--text-muted); font-size:.82rem; margin-bottom:6px; display:block; }
    .form-section { background:var(--bg-card); border:1px solid rgba(255,255,255,0.07); border-radius:14px; padding:1.4rem; margin-bottom:1.25rem; }
    .form-section-title { color:var(--text-white); font-weight:600; font-size:.95rem; margin-bottom:1.1rem; padding-bottom:.7rem; border-bottom:1px solid rgba(255,255,255,0.07); display:flex; align-items:center; gap:8px; }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.payments.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">
        Edit Tagihan — {{ $payment->tenant->name ?? '' }}
    </span>
</div>

<form method="POST" action="{{ route('admin.payments.update', $payment) }}">
@csrf @method('PUT')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-credit-card-fill" style="color:var(--accent);"></i>
                Detail Tagihan
            </div>

            {{-- Info penghuni (read only) --}}
            <div style="background:rgba(255,255,255,0.04); border-radius:10px;
                        padding:.85rem 1rem; margin-bottom:1rem;
                        display:flex; gap:12px; align-items:center;">
                <div style="width:40px; height:40px; background:rgba(255,140,50,0.15);
                            border-radius:50%; display:flex; align-items:center;
                            justify-content:center; color:var(--accent); font-weight:700; flex-shrink:0;">
                    {{ strtoupper(substr($payment->tenant->name ?? 'T', 0, 1)) }}
                </div>
                <div>
                    <div style="color:var(--text-white); font-weight:600; font-size:.9rem;">
                        {{ $payment->tenant->name ?? '—' }}
                    </div>
                    <div style="color:var(--text-muted); font-size:.78rem;">
                        {{ $payment->room->name ?? '—' }}
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="label-mk">Jumlah (Rp) *</label>
                    <input type="number" name="amount" class="form-mk"
                           value="{{ old('amount', $payment->amount) }}" min="0" required>
                    @error('amount') <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Jatuh Tempo *</label>
                    <input type="date" name="due_date" class="form-mk"
                           value="{{ old('due_date', $payment->due_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Status *</label>
                    <select name="status" class="form-mk" required
                            onchange="togglePaidDate(this.value)">
                        <option value="unpaid"  {{ old('status', $payment->status) == 'unpaid'  ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="paid"    {{ old('status', $payment->status) == 'paid'    ? 'selected' : '' }}>Lunas</option>
                        <option value="overdue" {{ old('status', $payment->status) == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
                <div class="col-md-6" id="paidDateWrapper"
                     style="{{ old('status', $payment->status) == 'paid' ? '' : 'display:none' }}">
                    <label class="label-mk">Tanggal Bayar</label>
                    <input type="date" name="paid_date" class="form-mk"
                           value="{{ old('paid_date', $payment->paid_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Metode</label>
                    <select name="method" class="form-mk">
                        <option value="">-- Pilih Metode --</option>
                        @foreach(['Transfer Bank','Cash','QRIS','OVO','GoPay','Dana'] as $m)
                        <option value="{{ $m }}"
                                {{ old('method', $payment->method) == $m ? 'selected' : '' }}>
                            {{ $m }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="label-mk">Catatan</label>
                    <textarea name="notes" class="form-mk" rows="2">{{ old('notes', $payment->notes) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-section" style="position:sticky; top:80px;">
            <div class="form-section-title">
                <i class="bi bi-receipt" style="color:var(--accent);"></i>
                Ringkasan
            </div>
            <div style="font-size:.85rem; color:var(--text-muted); line-height:2;">
                <div>Penghuni: <strong style="color:var(--text-white);">{{ $payment->tenant->name ?? '—' }}</strong></div>
                <div>Kamar: <strong style="color:var(--accent);">{{ $payment->room->name ?? '—' }}</strong></div>
                <div>Dibuat: <strong style="color:var(--text-white);">{{ $payment->created_at->format('d M Y') }}</strong></div>
            </div>
            <hr style="border-color:rgba(255,255,255,0.07); margin:1rem 0;">
            <button type="submit"
                    style="width:100%; background:var(--accent); color:#fff; border:none;
                           border-radius:10px; padding:12px; font-weight:600;
                           cursor:pointer; display:flex; align-items:center;
                           justify-content:center; gap:8px;"
                    onmouseover="this.style.opacity='.85'"
                    onmouseout="this.style.opacity='1'">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.payments.index') }}"
               style="display:block; text-align:center; margin-top:.75rem;
                      color:var(--text-muted); text-decoration:none; font-size:.85rem;">
                Batal
            </a>
        </div>
    </div>
</div>
</form>

@endsection

@push('scripts')
<script>
    function togglePaidDate(status) {
        document.getElementById('paidDateWrapper').style.display =
            status === 'paid' ? '' : 'none';
    }
</script>
@endpush