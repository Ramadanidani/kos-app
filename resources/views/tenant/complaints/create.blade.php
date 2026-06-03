@extends('layouts.tenant')

@section('title', 'Buat Keluhan')
@section('page-title', 'Buat Keluhan Baru')

@push('styles')
<style>
    .form-mk { background:transparent; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:.7rem 1rem; color:var(--text-white); font-size:.9rem; width:100%; transition:border-color .2s; }
    .form-mk:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(255,140,50,0.15); background:transparent; }
    .form-mk::placeholder { color:rgba(255,255,255,0.2); }
    .label-mk { color:var(--text-muted); font-size:.82rem; margin-bottom:6px; display:block; }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('tenant.complaints.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">Buat Keluhan Baru</span>
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

            <form method="POST" action="{{ route('tenant.complaints.store') }}">
                @csrf

                <div style="margin-bottom:1.25rem;">
                    <label class="label-mk">
                        Judul Keluhan <span style="color:#f87171;">*</span>
                    </label>
                    <input type="text" name="title" class="form-mk"
                           value="{{ old('title') }}"
                           placeholder="cth: AC Tidak Dingin, Lampu Mati, Kebocoran..."
                           maxlength="100" required>
                    <div style="color:var(--text-muted); font-size:.72rem; margin-top:4px;">
                        Tulis judul yang singkat dan jelas.
                    </div>
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label class="label-mk">
                        Deskripsi Keluhan <span style="color:#f87171;">*</span>
                    </label>
                    <textarea name="description" class="form-mk" rows="5"
                              placeholder="Jelaskan keluhan kamu secara detail — kapan terjadi, seberapa parah, dll."
                              maxlength="1000" required>{{ old('description') }}</textarea>
                    <div style="color:var(--text-muted); font-size:.72rem; margin-top:4px;">
                        Maksimal 1000 karakter.
                    </div>
                </div>

                <button type="submit"
                        style="background:var(--accent); color:#fff; border:none;
                               border-radius:10px; padding:12px 28px; font-weight:600;
                               cursor:pointer; font-size:.95rem; display:inline-flex;
                               align-items:center; gap:8px; transition:opacity .2s;"
                        onmouseover="this.style.opacity='.85'"
                        onmouseout="this.style.opacity='1'">
                    <i class="bi bi-send-fill"></i> Kirim Keluhan
                </button>

            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.07);
                    border-radius:14px; padding:1.25rem;">
            <h6 style="color:var(--text-white); font-weight:600; margin-bottom:.85rem;">
                <i class="bi bi-info-circle me-2" style="color:var(--accent);"></i>
                Info Pengajuan
            </h6>
            <div style="font-size:.82rem; color:var(--text-muted); line-height:1.9;">
                <div class="d-flex gap-2 mb-2">
                    <i class="bi bi-1-circle-fill" style="color:var(--accent); flex-shrink:0;"></i>
                    Isi judul dan deskripsi keluhan dengan jelas.
                </div>
                <div class="d-flex gap-2 mb-2">
                    <i class="bi bi-2-circle-fill" style="color:var(--accent); flex-shrink:0;"></i>
                    Keluhan akan diterima admin dan diproses segera.
                </div>
                <div class="d-flex gap-2 mb-2">
                    <i class="bi bi-3-circle-fill" style="color:var(--accent); flex-shrink:0;"></i>
                    Pantau status keluhan di halaman daftar keluhan.
                </div>
                <div class="d-flex gap-2">
                    <i class="bi bi-4-circle-fill" style="color:var(--accent); flex-shrink:0;"></i>
                    Admin akan memberikan catatan penanganan.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection