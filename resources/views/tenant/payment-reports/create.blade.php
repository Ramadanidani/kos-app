@extends('layouts.tenant')

@section('title', 'Kirim Laporan Pembayaran')
@section('page-title', 'Kirim Laporan Pembayaran')

@push('styles')
<style>
    .form-mk { background:transparent; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:.7rem 1rem; color:var(--text-white); font-size:.9rem; width:100%; transition:border-color .2s; }
    .form-mk:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(255,140,50,0.15); background:transparent; }
    .form-mk option { background:var(--bg-card); }
    .form-mk::placeholder { color:rgba(255,255,255,0.2); }
    .label-mk { color:var(--text-muted); font-size:.82rem; margin-bottom:6px; display:block; }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('tenant.payment-reports.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">Kirim Laporan Baru</span>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.07);
                    border-radius:14px; padding:1.5rem;">

            @if($errors->any())
            <div style="background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.2);
                        border-radius:10px; padding:12px 16px; margin-bottom:1.25rem;
                        color:#f87171; font-size:.85rem;">
                @foreach($errors->all() as $error)
                    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('tenant.payment-reports.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    {{-- Periode --}}
                    <div class="col-md-6">
                        <label class="label-mk">
                            Periode Pembayaran <span style="color:#f87171;">*</span>
                        </label>
                        <input type="month" name="period" class="form-mk"
                               value="{{ old('period', date('Y-m')) }}"
                               max="{{ date('Y-m') }}" required>
                        <div style="color:var(--text-muted); font-size:.72rem; margin-top:4px;">
                            Pilih bulan yang kamu bayar.
                        </div>
                        @error('period')
                            <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jumlah --}}
                    <div class="col-md-6">
                        <label class="label-mk">
                            Jumlah Dibayar (Rp) <span style="color:#f87171;">*</span>
                        </label>
                        <input type="number" name="amount" class="form-mk"
                               value="{{ old('amount', Auth::guard('tenant')->user()->room?->price) }}"
                               min="1000" required
                               placeholder="Sesuai jumlah yang dibayar">
                        @error('amount')
                            <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Metode --}}
                    <div class="col-12">
                        <label class="label-mk">
                            Metode Pembayaran <span style="color:#f87171;">*</span>
                        </label>
                        <div style="display:flex; flex-wrap:wrap; gap:8px;">
                            @foreach(['Transfer Bank', 'QRIS', 'Cash', 'OVO', 'GoPay', 'Dana'] as $m)
                            <label style="cursor:pointer;">
                                <input type="radio" name="method" value="{{ $m }}"
                                       {{ old('method') == $m ? 'checked' : '' }}
                                       style="display:none;"
                                       onchange="selectMethod('{{ $m }}', this)">
                                <span id="method_{{ Str::slug($m) }}"
                                      style="display:inline-block; padding:7px 16px;
                                             border:1px solid rgba(255,255,255,0.12);
                                             border-radius:20px; font-size:.82rem;
                                             color:var(--text-muted); transition:.2s;
                                             {{ old('method') == $m ? 'background:rgba(255,140,50,0.15); color:var(--accent); border-color:rgba(255,140,50,0.3);' : '' }}">
                                    {{ $m }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                        @error('method')
                            <div style="color:#f87171; font-size:.78rem; margin-top:6px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Upload Bukti --}}
                    <div class="col-12">
                        <label class="label-mk">
                            Foto Bukti Pembayaran <span style="color:#f87171;">*</span>
                        </label>
                        <div style="border:2px dashed rgba(255,255,255,0.12); border-radius:12px;
                                    padding:1.5rem; text-align:center; cursor:pointer; transition:.2s;"
                             onclick="document.getElementById('proofInput').click()"
                             onmouseover="this.style.borderColor='var(--accent)'"
                             onmouseout="this.style.borderColor='rgba(255,255,255,0.12)'">
                            <i class="bi bi-cloud-upload"
                               style="font-size:2.5rem; color:rgba(255,255,255,0.15);"></i>
                            <p style="color:var(--text-muted); margin:.5rem 0 0; font-size:.85rem;">
                                Klik untuk upload bukti transfer/QRIS/kwitansi
                            </p>
                            <p style="color:rgba(255,255,255,0.2); font-size:.72rem; margin:4px 0 0;">
                                JPG, PNG — Max 3MB
                            </p>
                        </div>
                        <input type="file" id="proofInput" name="proof_image"
                               accept="image/*" style="display:none"
                               onchange="previewProof(this)" required>

                        <div id="proofPreview" style="margin-top:1rem; display:none; text-align:center;">
                            <img id="proofPreviewImg"
                                 style="max-width:100%; max-height:280px; object-fit:contain;
                                        border-radius:10px; border:1px solid rgba(255,255,255,0.08);">
                            <div style="color:var(--text-muted); font-size:.75rem; margin-top:6px;">
                                Preview bukti pembayaran
                            </div>
                        </div>

                        @error('proof_image')
                            <div style="color:#f87171; font-size:.78rem; margin-top:6px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="col-12">
                        <label class="label-mk">Catatan (opsional)</label>
                        <textarea name="notes" class="form-mk" rows="3"
                                  placeholder="cth: Bayar cash ke admin, transfer atas nama istri, dll.">{{ old('notes') }}</textarea>
                    </div>

                </div>

                <button type="submit"
                        style="margin-top:1.5rem; background:var(--accent); color:#fff;
                               border:none; border-radius:10px; padding:12px 28px;
                               font-weight:600; cursor:pointer; font-size:.95rem;
                               display:inline-flex; align-items:center; gap:8px; transition:opacity .2s;"
                        onmouseover="this.style.opacity='.85'"
                        onmouseout="this.style.opacity='1'">
                    <i class="bi bi-send-fill"></i> Kirim Laporan
                </button>

            </form>
        </div>
    </div>

    {{-- Panduan --}}
    <div class="col-lg-4">
        <div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.07);
                    border-radius:14px; padding:1.25rem; position:sticky; top:80px;">
            <h6 style="color:var(--text-white); font-weight:600; margin-bottom:.85rem;">
                <i class="bi bi-info-circle me-2" style="color:var(--accent);"></i>
                Panduan Laporan
            </h6>
            <div style="font-size:.82rem; color:var(--text-muted); line-height:1.9;">
                <div class="d-flex gap-2 mb-2">
                    <i class="bi bi-1-circle-fill" style="color:var(--accent); flex-shrink:0; margin-top:2px;"></i>
                    Pilih periode bulan yang kamu bayar.
                </div>
                <div class="d-flex gap-2 mb-2">
                    <i class="bi bi-2-circle-fill" style="color:var(--accent); flex-shrink:0; margin-top:2px;"></i>
                    Isi jumlah sesuai yang benar-benar dibayar.
                </div>
                <div class="d-flex gap-2 mb-2">
                    <i class="bi bi-3-circle-fill" style="color:var(--accent); flex-shrink:0; margin-top:2px;"></i>
                    Upload foto bukti yang jelas dan terbaca.
                </div>
                <div class="d-flex gap-2 mb-2">
                    <i class="bi bi-4-circle-fill" style="color:var(--accent); flex-shrink:0; margin-top:2px;"></i>
                    Tambahkan catatan jika ada info tambahan.
                </div>
                <div class="d-flex gap-2">
                    <i class="bi bi-5-circle-fill" style="color:var(--accent); flex-shrink:0; margin-top:2px;"></i>
                    Admin akan verifikasi dan update tagihan.
                </div>
            </div>

            <hr style="border-color:rgba(255,255,255,0.07); margin:1rem 0;">

            <div style="background:rgba(255,140,50,0.08); border:1px solid rgba(255,140,50,0.15);
                        border-radius:10px; padding:.85rem; font-size:.8rem; color:var(--text-muted);">
                <i class="bi bi-exclamation-triangle me-1" style="color:var(--accent);"></i>
                Hanya bisa kirim <strong style="color:var(--accent);">1 laporan per bulan</strong>.
                Pastikan semua data sudah benar sebelum mengirim.
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function previewProof(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('proofPreviewImg').src = e.target.result;
                document.getElementById('proofPreview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    let selectedMethod = '{{ old('method') }}';

    function selectMethod(name, input) {
        // Reset semua
        document.querySelectorAll('[id^="method_"]').forEach(el => {
            el.style.background = 'transparent';
            el.style.color = 'var(--text-muted)';
            el.style.borderColor = 'rgba(255,255,255,0.12)';
        });
        // Highlight yang dipilih
        const id = 'method_' + name.toLowerCase().replace(/\s+/g, '-');
        const el = document.getElementById(id);
        if (el) {
            el.style.background = 'rgba(255,140,50,0.15)';
            el.style.color = 'var(--accent)';
            el.style.borderColor = 'rgba(255,140,50,0.3)';
        }
    }
</script>
@endpush