@extends('layouts.admin')

@section('title', 'Tambah Tagihan')
@section('page-title', 'Tambah Tagihan')

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
    <span style="color:var(--text-white); font-size:.88rem;">Tambah Tagihan Baru</span>
</div>

<form method="POST" action="{{ route('admin.payments.store') }}">
@csrf

<div class="row g-4">
    <div class="col-lg-8">
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-credit-card-fill" style="color:var(--accent);"></i>
                Detail Tagihan
            </div>
            <div class="row g-3">

                <div class="col-12">
                    <label class="label-mk">Penghuni <span style="color:#f87171;">*</span></label>
                    <select name="tenant_id" class="form-mk" required
                            onchange="autoFillAmount(this)">
                        <option value="">-- Pilih Penghuni --</option>
                        @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}"
                                data-price="{{ $tenant->room?->price ?? 0 }}"
                                {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name }} —
                            {{ $tenant->room?->name ?? 'Belum ada kamar' }}
                        </option>
                        @endforeach
                    </select>
                    @error('tenant_id')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="label-mk">Jumlah Tagihan (Rp) <span style="color:#f87171;">*</span></label>
                    <input type="number" name="amount" id="amountInput" class="form-mk"
                           value="{{ old('amount') }}" min="0" required
                           placeholder="Otomatis terisi dari harga kamar">
                    @error('amount')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="label-mk">Jatuh Tempo <span style="color:#f87171;">*</span></label>
                    <input type="date" name="due_date" class="form-mk"
                           value="{{ old('due_date', date('Y-m-d')) }}" required>
                    @error('due_date')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="label-mk">Status <span style="color:#f87171;">*</span></label>
                    <select name="status" class="form-mk" required
                            onchange="togglePaidDate(this.value)">
                        <option value="unpaid"  {{ old('status','unpaid') == 'unpaid'  ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="paid"    {{ old('status') == 'paid'    ? 'selected' : '' }}>Lunas</option>
                        <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>

                <div class="col-md-6" id="paidDateWrapper"
                     style="{{ old('status') == 'paid' ? '' : 'display:none' }}">
                    <label class="label-mk">Tanggal Bayar</label>
                    <input type="date" name="paid_date" class="form-mk"
                           value="{{ old('paid_date', date('Y-m-d')) }}">
                    @error('paid_date')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="label-mk">Metode Pembayaran</label>
                    <select name="method" class="form-mk">
                        <option value="">-- Pilih Metode --</option>
                        @foreach(['Transfer Bank','Cash','QRIS','OVO','GoPay','Dana'] as $m)
                        <option value="{{ $m }}" {{ old('method') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="label-mk">Catatan</label>
                    <textarea name="notes" class="form-mk" rows="2"
                              placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-section" style="position:sticky; top:80px;">
            <div class="form-section-title">
                <i class="bi bi-info-circle" style="color:var(--accent);"></i>
                Info
            </div>
            <div style="background:rgba(255,140,50,0.08); border:1px solid rgba(255,140,50,0.15);
                        border-radius:10px; padding:1rem; margin-bottom:1.25rem;
                        font-size:.82rem; color:var(--text-muted); line-height:1.8;">
                <i class="bi bi-lightbulb me-1" style="color:var(--accent);"></i>
                Pilih penghuni terlebih dahulu — jumlah tagihan akan otomatis terisi sesuai harga kamar.
            </div>
            <button type="submit"
                    style="width:100%; background:var(--accent); color:#fff; border:none;
                           border-radius:10px; padding:12px; font-weight:600;
                           cursor:pointer; display:flex; align-items:center;
                           justify-content:center; gap:8px;"
                    onmouseover="this.style.opacity='.85'"
                    onmouseout="this.style.opacity='1'">
                <i class="bi bi-check-lg"></i> Simpan Tagihan
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
    function autoFillAmount(select) {
        const opt   = select.options[select.selectedIndex];
        const price = opt.getAttribute('data-price');
        if (price) document.getElementById('amountInput').value = price;
    }

    function togglePaidDate(status) {
        const wrapper = document.getElementById('paidDateWrapper');
        wrapper.style.display = status === 'paid' ? '' : 'none';
    }
</script>
@endpush