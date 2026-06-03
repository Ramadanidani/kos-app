@extends('layouts.admin')

@section('title', 'Tambah Kamar')
@section('page-title', 'Tambah Kamar')

@push('styles')
<style>
    .form-mk {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px;
        padding: .7rem 1rem;
        color: var(--text-white);
        font-size: .9rem;
        width: 100%;
        transition: border-color .2s;
    }
    .form-mk:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(255,140,50,0.15);
        background: transparent;
    }
    .form-mk option { background: var(--bg-card); }
    .label-mk {
        color: var(--text-muted);
        font-size: .82rem;
        margin-bottom: 6px;
        display: block;
    }
    .form-section {
        background: var(--bg-card);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 14px;
        padding: 1.4rem;
        margin-bottom: 1.25rem;
    }
    .form-section-title {
        color: var(--text-white);
        font-weight: 600;
        font-size: .95rem;
        margin-bottom: 1.1rem;
        padding-bottom: .7rem;
        border-bottom: 1px solid rgba(255,255,255,0.07);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .photo-preview-area {
        border: 2px dashed rgba(255,255,255,0.12);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s;
    }
    .photo-preview-area:hover { border-color: var(--accent); }
    .facility-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        background: rgba(255,140,50,0.1);
        border: 1px solid rgba(255,140,50,0.2);
        border-radius: 999px;
        color: var(--accent);
        font-size: .8rem;
        margin: 3px;
        cursor: pointer;
        transition: .2s;
    }
    .facility-tag.selected {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.rooms.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">Tambah Kamar Baru</span>
</div>

<form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data">
@csrf

<div class="row g-4">

    {{-- KOLOM KIRI --}}
    <div class="col-lg-8">

        {{-- Info Dasar --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-info-circle" style="color:var(--accent);"></i>
                Informasi Dasar
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="label-mk">Nama Kamar <span style="color:#f87171;">*</span></label>
                    <input type="text" name="name" class="form-mk"
                           value="{{ old('name') }}" placeholder="cth: Kamar A1" required>
                    @error('name')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Tipe Kamar <span style="color:#f87171;">*</span></label>
                    <select name="type" class="form-mk" required>
                        <option value="">Pilih Tipe</option>
                        <option value="Standard" {{ old('type') == 'Standard' ? 'selected' : '' }}>Standard</option>
                        <option value="Deluxe"   {{ old('type') == 'Deluxe'   ? 'selected' : '' }}>Deluxe</option>
                        <option value="VIP"      {{ old('type') == 'VIP'      ? 'selected' : '' }}>VIP</option>
                    </select>
                    @error('type')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="label-mk">Harga/Bulan (Rp) <span style="color:#f87171;">*</span></label>
                    <input type="number" name="price" class="form-mk"
                           value="{{ old('price') }}" placeholder="800000" min="0" required>
                    @error('price')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="label-mk">Ukuran (m²) <span style="color:#f87171;">*</span></label>
                    <input type="number" name="size" class="form-mk"
                           value="{{ old('size') }}" placeholder="12" min="1" required>
                    @error('size')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="label-mk">Lantai <span style="color:#f87171;">*</span></label>
                    <input type="number" name="floor" class="form-mk"
                           value="{{ old('floor', 1) }}" placeholder="1" min="1" required>
                    @error('floor')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="label-mk">Deskripsi</label>
                    <textarea name="description" class="form-mk" rows="3"
                              placeholder="Deskripsi kamar...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Fasilitas --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-lightning-fill" style="color:var(--accent);"></i>
                Fasilitas
            </div>

            {{-- Quick select --}}
            <div class="mb-3">
                <p style="color:var(--text-muted); font-size:.8rem; margin-bottom:8px;">
                    Klik untuk pilih cepat:
                </p>
                @php
                    $quickFacilities = ['WiFi', 'AC', 'Kipas Angin', 'TV', 'Lemari',
                                        'Meja Belajar', 'Kamar Mandi Dalam', 'Kamar Mandi Luar',
                                        'Kulkas', 'Dapur Bersama', 'Parkir Motor', 'Parkir Mobil',
                                        'Balkon', 'Laundry'];
                @endphp
                <div id="facilityTags">
                    @foreach($quickFacilities as $f)
                    <span class="facility-tag" onclick="toggleFacility('{{ $f }}', this)">
                        {{ $f }}
                    </span>
                    @endforeach
                </div>
            </div>

            {{-- Input custom --}}
            <div class="d-flex gap-2 mb-2">
                <input type="text" id="customFacility" class="form-mk"
                       placeholder="Fasilitas lainnya..." style="flex:1;">
                <button type="button" onclick="addCustomFacility()"
                        style="background:rgba(255,140,50,0.15); color:var(--accent);
                               border:1px solid rgba(255,140,50,0.3); border-radius:10px;
                               padding:8px 16px; cursor:pointer; white-space:nowrap; font-size:.85rem;">
                    <i class="bi bi-plus-lg"></i> Tambah
                </button>
            </div>

            {{-- Hidden inputs --}}
            <div id="facilityInputs"></div>

            <div id="selectedFacilities" style="margin-top:8px; min-height:24px;">
                <p style="color:var(--text-muted); font-size:.78rem;" id="noFacilityMsg">
                    Belum ada fasilitas dipilih.
                </p>
            </div>
        </div>

        {{-- Upload Foto --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-images" style="color:var(--accent);"></i>
                Foto Kamar
            </div>

            <div class="photo-preview-area" onclick="document.getElementById('photoInput').click()">
                <i class="bi bi-cloud-upload" style="font-size:2.5rem; color:rgba(255,255,255,0.15);"></i>
                <p style="color:var(--text-muted); margin:.5rem 0 0; font-size:.88rem;">
                    Klik untuk upload foto kamar
                </p>
                <p style="color:rgba(255,255,255,0.2); font-size:.75rem; margin:4px 0 0;">
                    JPG, PNG, WebP — Max 2MB per foto
                </p>
            </div>
            <input type="file" id="photoInput" name="photos[]"
                   multiple accept="image/*" style="display:none"
                   onchange="previewPhotos(this)">

            <div id="photoPreviewContainer" class="d-flex flex-wrap gap-2 mt-3"></div>

            @error('photos.*')
                <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-lg-4">
        <div class="form-section" style="position:sticky; top:80px;">
            <div class="form-section-title">
                <i class="bi bi-toggle-on" style="color:var(--accent);"></i>
                Status & Publish
            </div>

            <label class="label-mk">Status Kamar <span style="color:#f87171;">*</span></label>
            <select name="status" class="form-mk mb-4" required>
                <option value="available"   {{ old('status', 'available') == 'available'   ? 'selected' : '' }}>Tersedia</option>
                <option value="occupied"    {{ old('status') == 'occupied'    ? 'selected' : '' }}>Terisi</option>
                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>

            <button type="submit"
                    style="width:100%; background:var(--accent); color:#fff; border:none;
                           border-radius:10px; padding:12px; font-weight:600; font-size:.95rem;
                           cursor:pointer; transition:opacity .2s; display:flex;
                           align-items:center; justify-content:center; gap:8px;"
                    onmouseover="this.style.opacity='.85'"
                    onmouseout="this.style.opacity='1'">
                <i class="bi bi-check-lg"></i> Simpan Kamar
            </button>

            <a href="{{ route('admin.rooms.index') }}"
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
    // ── Fasilitas ──
    let selectedFacilities = [];

    function toggleFacility(name, el) {
        if (selectedFacilities.includes(name)) {
            selectedFacilities = selectedFacilities.filter(f => f !== name);
            el.classList.remove('selected');
        } else {
            selectedFacilities.push(name);
            el.classList.add('selected');
        }
        renderFacilities();
    }

    function addCustomFacility() {
        const input = document.getElementById('customFacility');
        const val = input.value.trim();
        if (val && !selectedFacilities.includes(val)) {
            selectedFacilities.push(val);
            renderFacilities();
            input.value = '';
        }
    }

    function removeFacility(name) {
        selectedFacilities = selectedFacilities.filter(f => f !== name);
        // Unselect tag jika ada
        document.querySelectorAll('.facility-tag').forEach(tag => {
            if (tag.textContent.trim() === name) tag.classList.remove('selected');
        });
        renderFacilities();
    }

    function renderFacilities() {
        const container = document.getElementById('selectedFacilities');
        const inputs    = document.getElementById('facilityInputs');
        const noMsg     = document.getElementById('noFacilityMsg');

        inputs.innerHTML = '';
        container.innerHTML = '';

        if (selectedFacilities.length === 0) {
            container.innerHTML = '<p style="color:var(--text-muted); font-size:.78rem;">Belum ada fasilitas dipilih.</p>';
            return;
        }

        selectedFacilities.forEach(f => {
            // Hidden input
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'facilities[]';
            inp.value = f;
            inputs.appendChild(inp);

            // Tag tampilan
            const tag = document.createElement('span');
            tag.style.cssText = 'display:inline-flex; align-items:center; gap:6px; padding:4px 10px; background:rgba(34,197,94,0.12); border:1px solid rgba(34,197,94,0.2); border-radius:999px; color:#4ade80; font-size:.78rem; margin:3px;';
            tag.innerHTML = `${f} <i class="bi bi-x" style="cursor:pointer;" onclick="removeFacility('${f}')"></i>`;
            container.appendChild(tag);
        });
    }

    // ── Preview Foto ──
    function previewPhotos(input) {
        const container = document.getElementById('photoPreviewContainer');
        container.innerHTML = '';

        Array.from(input.files).forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrap = document.createElement('div');
                wrap.style.cssText = 'position:relative; width:100px; height:80px;';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'width:100%; height:100%; object-fit:cover; border-radius:8px; display:block;';

                const label = document.createElement('div');
                label.style.cssText = 'position:absolute; bottom:4px; left:4px; background:rgba(0,0,0,.6); color:#fff; font-size:.6rem; padding:2px 6px; border-radius:4px;';
                label.textContent = i === 0 ? 'Utama' : `Foto ${i+1}`;

                wrap.appendChild(img);
                wrap.appendChild(label);
                container.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });
    }

    // Enter di custom facility
    document.getElementById('customFacility')?.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); addCustomFacility(); }
    });
</script>
@endpush