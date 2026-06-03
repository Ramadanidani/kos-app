@extends('layouts.admin')

@section('title', 'Tambah Penghuni')
@section('page-title', 'Tambah Penghuni')

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
    <a href="{{ route('admin.tenants.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">Tambah Penghuni Baru</span>
</div>

<form method="POST" action="{{ route('admin.tenants.store') }}">
@csrf

<div class="row g-4">

    {{-- KIRI --}}
    <div class="col-lg-8">

        {{-- Data Diri --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-person-fill" style="color:var(--accent);"></i>
                Data Diri Penghuni
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="label-mk">Nama Lengkap <span style="color:#f87171;">*</span></label>
                    <input type="text" name="name" class="form-mk"
                           value="{{ old('name') }}" placeholder="cth: Budi Santoso" required>
                    @error('name')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-mk">No. HP <span style="color:#f87171;">*</span></label>
                    <div style="position:relative;">
                        <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%);
                                     color:var(--text-muted); font-size:.88rem;">+62</span>
                        <input type="text" name="phone" class="form-mk"
                               value="{{ old('phone') }}" placeholder="8123456789"
                               style="padding-left:42px;" required>
                    </div>
                    <div style="color:var(--text-muted); font-size:.72rem; margin-top:4px;">
                        Digunakan untuk login penghuni.
                    </div>
                    @error('phone')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-mk">No. KTP</label>
                    <input type="text" name="id_card" class="form-mk"
                           value="{{ old('id_card') }}" placeholder="3201xxxxxxxxxxxxxxxx"
                           maxlength="16">
                    @error('id_card')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Password Sementara <span style="color:#f87171;">*</span></label>
                    <input type="text" name="password" class="form-mk"
                        value="{{ old('password', '123456') }}"
                        placeholder="Min. 6 karakter" required>
                    <div style="color:var(--text-muted); font-size:.72rem; margin-top:4px;">
                        <i class="bi bi-info-circle me-1"></i>
                        Penghuni wajib ganti password saat login pertama.
                    </div>
                    @error('password')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                                <div class="col-12">
                    <label class="label-mk">Catatan</label>
                    <textarea name="notes" class="form-mk" rows="2"
                              placeholder="Catatan tambahan tentang penghuni...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Info Sewa --}}
        <div class="form-section">
            <div class="form-section-title">
                <i class="bi bi-house-fill" style="color:var(--accent);"></i>
                Informasi Sewa
            </div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="label-mk">Pilih Kamar <span style="color:#f87171;">*</span></label>
                    <select name="room_id" class="form-mk" required>
                        <option value="">-- Pilih Kamar Tersedia --</option>
                        @foreach($rooms as $room)
                        <option value="{{ $room->id }}"
                                {{ old('room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->name }} — {{ $room->type }} —
                            Rp {{ number_format($room->price, 0, ',', '.') }}/bln
                            (Lantai {{ $room->floor }}, {{ $room->size }}m²)
                        </option>
                        @endforeach
                    </select>
                    @if($rooms->isEmpty())
                        <div style="color:#fbbf24; font-size:.78rem; margin-top:4px;">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Tidak ada kamar tersedia saat ini.
                        </div>
                    @endif
                    @error('room_id')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Tanggal Mulai Sewa <span style="color:#f87171;">*</span></label>
                    <input type="date" name="start_date" class="form-mk"
                           value="{{ old('start_date', date('Y-m-d')) }}" required>
                    @error('start_date')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Tanggal Akhir Sewa</label>
                    <input type="date" name="end_date" class="form-mk"
                           value="{{ old('end_date') }}">
                    <div style="color:var(--text-muted); font-size:.72rem; margin-top:4px;">
                        Kosongkan jika belum ditentukan.
                    </div>
                    @error('end_date')
                        <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

    </div>

    {{-- KANAN --}}
    <div class="col-lg-4">
        <div class="form-section" style="position:sticky; top:80px;">
            <div class="form-section-title">
                <i class="bi bi-info-circle" style="color:var(--accent);"></i>
                Ringkasan
            </div>

            <div style="background:rgba(255,140,50,0.08); border:1px solid rgba(255,140,50,0.15);
                        border-radius:10px; padding:1rem; margin-bottom:1.25rem;">
                <p style="color:var(--text-muted); font-size:.8rem; margin:0 0 6px;">
                    <i class="bi bi-info-circle me-1"></i>
                    Setelah penghuni ditambahkan:
                </p>
                <ul style="color:var(--text-muted); font-size:.8rem; margin:0; padding-left:1.2rem; line-height:1.8;">
                    <li>Status kamar otomatis jadi <strong style="color:#f87171;">Terisi</strong></li>
                    <li>No. HP digunakan untuk login penghuni</li>
                </ul>
            </div>

            <button type="submit"
                    style="width:100%; background:var(--accent); color:#fff; border:none;
                           border-radius:10px; padding:12px; font-weight:600; font-size:.95rem;
                           cursor:pointer; display:flex; align-items:center;
                           justify-content:center; gap:8px;"
                    onmouseover="this.style.opacity='.85'"
                    onmouseout="this.style.opacity='1'">
                <i class="bi bi-person-plus"></i> Tambah Penghuni
            </button>

            <a href="{{ route('admin.tenants.index') }}"
               style="display:block; text-align:center; margin-top:.75rem;
                      color:var(--text-muted); text-decoration:none; font-size:.85rem;">
                Batal
            </a>
        </div>
    </div>

</div>
</form>

@endsection