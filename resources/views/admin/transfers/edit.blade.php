@extends('layouts.admin')

@section('title', 'Edit Penghuni')
@section('page-title', 'Edit Penghuni')

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
    <span style="color:var(--text-white); font-size:.88rem;">Edit: {{ $tenant->name }}</span>
</div>

<form method="POST" action="{{ route('admin.tenants.update', $tenant) }}">
@csrf @method('PUT')

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
                    <label class="label-mk">Nama Lengkap *</label>
                    <input type="text" name="name" class="form-mk"
                           value="{{ old('name', $tenant->name) }}" required>
                    @error('name') <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-mk">No. HP *</label>
                    <input type="text" name="phone" class="form-mk"
                           value="{{ old('phone', $tenant->phone) }}" required>
                    @error('phone') <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-mk">No. KTP</label>
                    <input type="text" name="id_card" class="form-mk"
                           value="{{ old('id_card', $tenant->id_card) }}" maxlength="16">
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Reset Password</label>
                    <input type="text" name="password" class="form-mk"
                        placeholder="Kosongkan jika tidak ingin reset">
                    <div style="color:var(--text-muted); font-size:.72rem; margin-top:4px;">
                        <i class="bi bi-info-circle me-1"></i>
                        Isi jika ingin reset password penghuni.
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Status Penghuni *</label>
                    <select name="status" class="form-mk" required>
                        <option value="active"   {{ old('status', $tenant->status) == 'active'   ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $tenant->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="label-mk">Catatan</label>
                    <textarea name="notes" class="form-mk" rows="2">{{ old('notes', $tenant->notes) }}</textarea>
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
                    <label class="label-mk">Kamar *</label>
                    <select name="room_id" class="form-mk" required>
                        @foreach($rooms as $room)
                        <option value="{{ $room->id }}"
                                {{ old('room_id', $tenant->room_id) == $room->id ? 'selected' : '' }}>
                            {{ $room->name }} — {{ $room->type }} —
                            Rp {{ number_format($room->price, 0, ',', '.') }}/bln
                            {{ $room->id == $tenant->room_id ? '(Kamar Saat Ini)' : '' }}
                        </option>
                        @endforeach
                    </select>
                    @error('room_id') <div style="color:#f87171; font-size:.78rem; margin-top:4px;">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Tanggal Mulai *</label>
                    <input type="date" name="start_date" class="form-mk"
                           value="{{ old('start_date', $tenant->start_date?->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="label-mk">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-mk"
                           value="{{ old('end_date', $tenant->end_date?->format('Y-m-d')) }}">
                </div>
            </div>
        </div>

    </div>

    {{-- KANAN --}}
    <div class="col-lg-4">
        <div class="form-section" style="position:sticky; top:80px;">
            <div class="form-section-title">
                <i class="bi bi-person-badge" style="color:var(--accent);"></i>
                Info Penghuni
            </div>

            {{-- Avatar --}}
            <div style="text-align:center; margin-bottom:1.25rem;">
                <div style="width:64px; height:64px; background:rgba(255,140,50,0.15);
                            border-radius:50%; display:flex; align-items:center;
                            justify-content:center; margin:0 auto 10px;
                            color:var(--accent); font-size:1.6rem; font-weight:700;">
                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                </div>
                <div style="color:var(--text-white); font-weight:600;">{{ $tenant->name }}</div>
                <div style="color:var(--text-muted); font-size:.82rem;">{{ $tenant->phone }}</div>
            </div>

            <div style="background:rgba(255,255,255,0.04); border-radius:10px;
                        padding:.85rem; margin-bottom:1.25rem; font-size:.82rem;">
                <div style="color:var(--text-muted); margin-bottom:6px;">
                    <i class="bi bi-door-open me-1"></i>
                    Kamar saat ini:
                    <strong style="color:var(--accent);">
                        {{ $tenant->room->name ?? '—' }}
                    </strong>
                </div>
                <div style="color:var(--text-muted);">
                    <i class="bi bi-calendar me-1"></i>
                    Mulai: {{ $tenant->start_date?->format('d M Y') ?? '—' }}
                </div>
            </div>

            <button type="submit"
                    style="width:100%; background:var(--accent); color:#fff; border:none;
                           border-radius:10px; padding:12px; font-weight:600;
                           cursor:pointer; display:flex; align-items:center;
                           justify-content:center; gap:8px;"
                    onmouseover="this.style.opacity='.85'"
                    onmouseout="this.style.opacity='1'">
                <i class="bi bi-check-lg"></i> Simpan Perubahan
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