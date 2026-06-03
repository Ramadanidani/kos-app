@extends('layouts.admin')

@section('title', 'Info Pembayaran')
@section('page-title', 'Info Pembayaran')

@push('styles')
<style>
    .form-mk { background:transparent; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:.7rem 1rem; color:var(--text-white); font-size:.9rem; width:100%; transition:border-color .2s; }
    .form-mk:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(255,140,50,0.15); background:transparent; }
    .form-mk::placeholder { color:rgba(255,255,255,0.25); }
    .label-mk { color:var(--text-muted); font-size:.82rem; margin-bottom:6px; display:block; }
    .form-section { background:var(--bg-card); border:1px solid rgba(255,255,255,0.07); border-radius:14px; padding:1.4rem; margin-bottom:1.25rem; }
    .form-section-title { color:var(--text-white); font-weight:600; font-size:.95rem; margin-bottom:1.1rem; padding-bottom:.7rem; border-bottom:1px solid rgba(255,255,255,0.07); display:flex; align-items:center; gap:8px; }
</style>
@endpush

@section('content')

<div class="mb-4">
    <p style="color:var(--text-muted); margin:0; font-size:.88rem;">
        Informasi ini akan ditampilkan kepada penghuni di halaman tagihan.
    </p>
</div>

<form method="POST" action="{{ route('admin.payment-info.update') }}"
      enctype="multipart/form-data">
@csrf @method('PUT')

<div class="row g-4">

    {{-- KIRI --}}
    <div class="col-lg-8">

        {{-- Info Rekening Bank --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-bank" style="color:var(--accent);"></i>
                Transfer Bank
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="label-mk">Nama Bank</label>
                    <select name="bank_name" class="form-mk"
                            style="appearance:auto;">
                        <option value="">-- Pilih Bank --</option>
                        @foreach(['BCA','Mandiri','BNI','BRI','BSI','CIMB Niaga',
                                  'Permata','Danamon','BTN','Maybank'] as $bank)
                        <option value="{{ $bank }}"
                                {{ old('bank_name', $info->bank_name) == $bank ? 'selected':'' }}
                                style="background:var(--bg-card);">
                            {{ $bank }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="label-mk">Nomor Rekening</label>
                    <input type="text" name="account_number" class="form-mk"
                           value="{{ old('account_number', $info->account_number) }}"
                           placeholder="cth: 1234567890">
                    @error('account_number')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="label-mk">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" class="form-mk"
                           value="{{ old('account_name', $info->account_name) }}"
                           placeholder="cth: Budi Santoso">
                    @error('account_name')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- QRIS --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-qr-code-scan" style="color:var(--accent);"></i>
                QRIS
            </div>

            @if($info->qris_image)
            <div style="margin-bottom:1rem;">
                <div style="color:var(--text-muted); font-size:.78rem; margin-bottom:8px;">
                    QRIS Saat Ini:
                </div>
                <div style="display:inline-block; background:white; padding:12px;
                            border-radius:12px; position:relative;">
                    <img src="{{ asset('storage/' . $info->qris_image) }}"
                         style="width:180px; height:180px; object-fit:contain; display:block;"
                         alt="QRIS">
                </div>
                <div style="margin-top:8px; font-size:.78rem; color:var(--text-muted);">
                    Upload gambar baru untuk mengganti QRIS.
                </div>
            </div>
            @endif

            <div style="border:2px dashed rgba(255,255,255,0.12); border-radius:12px;
                        padding:1.5rem; text-align:center; cursor:pointer; transition:.2s;"
                 onclick="document.getElementById('qrisInput').click()"
                 onmouseover="this.style.borderColor='var(--accent)'"
                 onmouseout="this.style.borderColor='rgba(255,255,255,0.12)'">
                <i class="bi bi-qr-code" style="font-size:2.5rem; color:rgba(255,255,255,0.15);"></i>
                <p style="color:var(--text-muted); margin:.5rem 0 0; font-size:.85rem;">
                    {{ $info->qris_image ? 'Klik untuk ganti foto QRIS' : 'Klik untuk upload foto QRIS' }}
                </p>
                <p style="color:rgba(255,255,255,0.2); font-size:.72rem; margin:4px 0 0;">
                    JPG, PNG — Max 2MB
                </p>
            </div>
            <input type="file" id="qrisInput" name="qris_image"
                   accept="image/*" style="display:none"
                   onchange="previewQris(this)">
            <div id="qrisPreview" style="margin-top:1rem; display:none; text-align:center;">
                <img id="qrisPreviewImg"
                     style="width:180px; height:180px; object-fit:contain;
                            background:white; padding:12px; border-radius:12px;">
                <div style="color:var(--text-muted); font-size:.75rem; margin-top:6px;">
                    Preview QRIS baru
                </div>
            </div>
            @error('qris_image')
                <div style="color:#f87171; font-size:.78rem; margin-top:6px;">{{ $message }}</div>
            @enderror
        </div>

        {{-- WhatsApp & Catatan --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-whatsapp" style="color:var(--accent);"></i>
                Kontak & Catatan
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="label-mk">No. WhatsApp Admin</label>
                    <div style="position:relative;">
                        <span style="position:absolute; left:12px; top:50%;
                                     transform:translateY(-50%);
                                     color:var(--text-muted); font-size:.88rem;">+62</span>
                        <input type="text" name="whatsapp" class="form-mk"
                               value="{{ old('whatsapp', $info->whatsapp) }}"
                               placeholder="8123456789"
                               style="padding-left:42px;">
                    </div>
                    <div style="color:var(--text-muted); font-size:.72rem; margin-top:4px;">
                        Penghuni akan diarahkan ke nomor ini untuk konfirmasi.
                    </div>
                </div>
                <div class="col-12">
                    <label class="label-mk">Catatan Tambahan</label>
                    <textarea name="notes" class="form-mk" rows="3"
                              placeholder="cth: Transfer setiap tanggal 1-5, sertakan nama dan nomor kamar...">{{ old('notes', $info->notes) }}</textarea>
                </div>
            </div>
        </div>

    </div>

    {{-- KANAN: Preview --}}
    <div class="col-lg-4">
        <div class="form-section" style="position:sticky; top:80px;">
            <div class="form-section-title">
                <i class="bi bi-eye" style="color:var(--accent);"></i>
                Preview Tampilan
            </div>
            <p style="color:var(--text-muted); font-size:.78rem; margin-bottom:1rem;">
                Begini tampilan yang dilihat penghuni:
            </p>

            {{-- Preview card --}}
            <div style="background:var(--bg-main); border:1px solid rgba(255,255,255,0.07);
                        border-radius:12px; padding:1rem; font-size:.82rem;">

                @if($info->bank_name || $info->account_number)
                <div style="margin-bottom:.85rem;">
                    <div style="color:var(--text-muted); font-size:.72rem; margin-bottom:4px;">
                        Transfer Bank
                    </div>
                    <div style="color:var(--text-white); font-weight:600;">
                        {{ $info->bank_name ?? '—' }}
                    </div>
                    <div style="color:var(--accent); font-weight:700; font-size:1rem;">
                        {{ $info->account_number ?? '—' }}
                    </div>
                    <div style="color:var(--text-muted);">
                        a/n {{ $info->account_name ?? '—' }}
                    </div>
                </div>
                @endif

                @if($info->qris_image)
                <div style="text-align:center; margin-bottom:.85rem;">
                    <div style="color:var(--text-muted); font-size:.72rem; margin-bottom:6px;">QRIS</div>
                    <img src="{{ asset('storage/' . $info->qris_image) }}"
                         style="width:100px; height:100px; object-fit:contain;
                                background:white; padding:8px; border-radius:8px;">
                </div>
                @endif

                @if(!$info->bank_name && !$info->qris_image)
                <div style="text-align:center; color:var(--text-muted); padding:.5rem 0;">
                    <i class="bi bi-credit-card" style="font-size:1.5rem;"></i>
                    <p style="font-size:.78rem; margin:.5rem 0 0;">
                        Isi data di sebelah kiri untuk preview
                    </p>
                </div>
                @endif

            </div>

            <button type="submit"
                    style="width:100%; margin-top:1.25rem; background:var(--accent);
                           color:#fff; border:none; border-radius:10px; padding:12px;
                           font-weight:600; cursor:pointer; display:flex;
                           align-items:center; justify-content:center; gap:8px;"
                    onmouseover="this.style.opacity='.85'"
                    onmouseout="this.style.opacity='1'">
                <i class="bi bi-check-lg"></i> Simpan Informasi
            </button>
        </div>
    </div>

</div>
</form>

@endsection

@push('scripts')
<script>
    function previewQris(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('qrisPreviewImg').src = e.target.result;
                document.getElementById('qrisPreview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush