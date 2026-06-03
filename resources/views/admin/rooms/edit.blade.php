@extends('layouts.admin')

@section('title', 'Edit ' . $room->name)
@section('page-title', 'Edit Kamar')

@push('styles')
{{-- Reuse style dari create --}}
<style>
    .form-mk { background:transparent; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:.7rem 1rem; color:var(--text-white); font-size:.9rem; width:100%; transition:border-color .2s; }
    .form-mk:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(255,140,50,0.15); background:transparent; }
    .form-mk option { background:var(--bg-card); }
    .label-mk { color:var(--text-muted); font-size:.82rem; margin-bottom:6px; display:block; }
    .form-section { background:var(--bg-card); border:1px solid rgba(255,255,255,0.07); border-radius:14px; padding:1.4rem; margin-bottom:1.25rem; }
    .form-section-title { color:var(--text-white); font-weight:600; font-size:.95rem; margin-bottom:1.1rem; padding-bottom:.7rem; border-bottom:1px solid rgba(255,255,255,0.07); display:flex; align-items:center; gap:8px; }
    .facility-tag { display:inline-flex; align-items:center; gap:6px; padding:5px 10px; background:rgba(255,140,50,0.1); border:1px solid rgba(255,140,50,0.2); border-radius:999px; color:var(--accent); font-size:.8rem; margin:3px; cursor:pointer; transition:.2s; }
    .facility-tag.selected { background:var(--accent); color:#fff; border-color:var(--accent); }
    .photo-delete-overlay { position:absolute; top:4px; right:4px; width:22px; height:22px; background:rgba(239,68,68,.85); border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#fff; font-size:.7rem; }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.rooms.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">Edit: {{ $room->name }}</span>
</div>

<form method="POST" action="{{ route('admin.rooms.update', $room) }}" enctype="multipart/form-data">
@csrf @method('PUT')

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
                    <label class="label-mk">Nama Kamar *</label>
                    <input type="text" name="name" class="form-mk"
                           value="{{ old('name', $room->name) }}" required>
                    @error('name') <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Tipe Kamar *</label>
                    <select name="type" class="form-mk" required>
                        @foreach(['Standard','Deluxe','VIP'] as $t)
                        <option value="{{ $t }}" {{ old('type', $room->type) == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="label-mk">Harga/Bulan (Rp) *</label>
                    <input type="number" name="price" class="form-mk"
                           value="{{ old('price', $room->price) }}" min="0" required>
                </div>
                <div class="col-md-4">
                    <label class="label-mk">Ukuran (m²) *</label>
                    <input type="number" name="size" class="form-mk"
                           value="{{ old('size', $room->size) }}" min="1" required>
                </div>
                <div class="col-md-4">
                    <label class="label-mk">Lantai *</label>
                    <input type="number" name="floor" class="form-mk"
                           value="{{ old('floor', $room->floor) }}" min="1" required>
                </div>
                <div class="col-12">
                    <label class="label-mk">Deskripsi</label>
                    <textarea name="description" class="form-mk" rows="3">{{ old('description', $room->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Fasilitas --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-lightning-fill" style="color:var(--accent);"></i>
                Fasilitas
            </div>
            @php
                $quickFacilities = ['WiFi','AC','Kipas Angin','TV','Lemari','Meja Belajar',
                                    'Kamar Mandi Dalam','Kamar Mandi Luar','Kulkas',
                                    'Dapur Bersama','Parkir Motor','Parkir Mobil','Balkon','Laundry'];
                $existingFacilities = old('facilities', $room->facilities ?? []);
            @endphp
            <div id="facilityTags" class="mb-3">
                @foreach($quickFacilities as $f)
                <span class="facility-tag {{ in_array($f, $existingFacilities) ? 'selected' : '' }}"
                      onclick="toggleFacility('{{ $f }}', this)">{{ $f }}</span>
                @endforeach
            </div>
            <div class="d-flex gap-2 mb-2">
                <input type="text" id="customFacility" class="form-mk"
                       placeholder="Fasilitas lainnya..." style="flex:1;">
                <button type="button" onclick="addCustomFacility()"
                        style="background:rgba(255,140,50,0.15); color:var(--accent); border:1px solid rgba(255,140,50,0.3); border-radius:10px; padding:8px 16px; cursor:pointer; font-size:.85rem;">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
            <div id="facilityInputs"></div>
            <div id="selectedFacilities" style="margin-top:8px;"></div>
        </div>

        {{-- Foto yang ada --}}
        @if($room->photos->count() > 0)
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-images" style="color:var(--accent);"></i>
                Foto Saat Ini
                <span style="font-size:.78rem; color:var(--text-muted); font-weight:400;">
                    (centang untuk hapus)
                </span>
            </div>
            <div class="d-flex flex-wrap gap-3">
                @foreach($room->photos as $photo)
                <div style="position:relative; width:110px;">
                    <img src="{{ asset('storage/' . $photo->photo_path) }}"
                         style="width:110px; height:85px; object-fit:cover;
                                border-radius:10px; display:block;
                                border:2px solid {{ $photo->is_primary ? 'var(--accent)' : 'transparent' }};">
                    @if($photo->is_primary)
                    <span style="position:absolute; bottom:4px; left:4px; background:var(--accent);
                                 color:#fff; font-size:.6rem; padding:2px 6px; border-radius:4px;">
                        Utama
                    </span>
                    @endif
                    <label style="position:absolute; top:4px; right:4px; cursor:pointer;">
                        <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}"
                               style="display:none;" id="del_{{ $photo->id }}"
                               onchange="toggleDeletePhoto(this, '{{ $photo->id }}')">
                        <div id="del_overlay_{{ $photo->id }}"
                             style="width:22px; height:22px; background:rgba(0,0,0,.5);
                                    border-radius:50%; display:flex; align-items:center;
                                    justify-content:center; color:#fff; font-size:.75rem;
                                    border:1px solid rgba(255,255,255,.2);">
                            <i class="bi bi-trash"></i>
                        </div>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Upload foto baru --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-cloud-upload" style="color:var(--accent);"></i>
                Tambah Foto Baru
            </div>
            <div style="border:2px dashed rgba(255,255,255,0.12); border-radius:12px;
                        padding:1.5rem; text-align:center; cursor:pointer;"
                 onclick="document.getElementById('photoInput').click()"
                 onmouseover="this.style.borderColor='var(--accent)'"
                 onmouseout="this.style.borderColor='rgba(255,255,255,0.12)'">
                <i class="bi bi-cloud-upload" style="font-size:2rem; color:rgba(255,255,255,0.15);"></i>
                <p style="color:var(--text-muted); margin:.4rem 0 0; font-size:.85rem;">
                    Klik untuk upload foto tambahan
                </p>
            </div>
            <input type="file" id="photoInput" name="photos[]" multiple
                   accept="image/*" style="display:none" onchange="previewPhotos(this)">
            <div id="photoPreviewContainer" class="d-flex flex-wrap gap-2 mt-3"></div>
        </div>

    </div>

    {{-- KOLOM KANAN --}}
    <div class="col-lg-4">
        <div class="form-section" style="position:sticky; top:80px;">
            <div class="form-section-title">
                <i class="bi bi-toggle-on" style="color:var(--accent);"></i>
                Status Kamar
            </div>
            <label class="label-mk">Status *</label>
            <select name="status" class="form-mk mb-4" required>
                <option value="available"   {{ old('status', $room->status) == 'available'   ? 'selected' : '' }}>Tersedia</option>
                <option value="occupied"    {{ old('status', $room->status) == 'occupied'    ? 'selected' : '' }}>Terisi</option>
                <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>

            <button type="submit"
                    style="width:100%; background:var(--accent); color:#fff; border:none;
                           border-radius:10px; padding:12px; font-weight:600;
                           cursor:pointer; display:flex; align-items:center;
                           justify-content:center; gap:8px;"
                    onmouseover="this.style.opacity='.85'"
                    onmouseout="this.style.opacity='1'">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
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
    // Init fasilitas dari data existing
    let selectedFacilities = @json(old('facilities', $room->facilities ?? []));

    // Render saat load
    renderFacilities();

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
        document.querySelectorAll('.facility-tag').forEach(tag => {
            if (tag.textContent.trim() === name) tag.classList.remove('selected');
        });
        renderFacilities();
    }

    function renderFacilities() {
        const container = document.getElementById('selectedFacilities');
        const inputs    = document.getElementById('facilityInputs');
        inputs.innerHTML = '';
        container.innerHTML = '';
        if (selectedFacilities.length === 0) {
            container.innerHTML = '<p style="color:var(--text-muted);font-size:.78rem;">Belum ada fasilitas dipilih.</p>';
            return;
        }
        selectedFacilities.forEach(f => {
            const inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'facilities[]'; inp.value = f;
            inputs.appendChild(inp);
            const tag = document.createElement('span');
            tag.style.cssText = 'display:inline-flex;align-items:center;gap:6px;padding:4px 10px;background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.2);border-radius:999px;color:#4ade80;font-size:.78rem;margin:3px;';
            tag.innerHTML = `${f} <i class="bi bi-x" style="cursor:pointer;" onclick="removeFacility('${f}')"></i>`;
            container.appendChild(tag);
        });
    }

    function previewPhotos(input) {
        const container = document.getElementById('photoPreviewContainer');
        container.innerHTML = '';
        Array.from(input.files).forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrap = document.createElement('div');
                wrap.style.cssText = 'position:relative;width:100px;height:80px;';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'width:100%;height:100%;object-fit:cover;border-radius:8px;display:block;';
                wrap.appendChild(img);
                container.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });
    }

    function toggleDeletePhoto(checkbox, id) {
        const overlay = document.getElementById('del_overlay_' + id);
        if (checkbox.checked) {
            overlay.style.background = 'rgba(239,68,68,.85)';
            overlay.innerHTML = '<i class="bi bi-check-lg"></i>';
        } else {
            overlay.style.background = 'rgba(0,0,0,.5)';
            overlay.innerHTML = '<i class="bi bi-trash"></i>';
        }
    }

    document.getElementById('customFacility')?.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); addCustomFacility(); }
    });
</script>
@endpush