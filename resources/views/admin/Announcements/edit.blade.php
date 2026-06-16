@extends('layouts.admin')

@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')

@push('styles')
<style>
    .form-mk {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px;
        padding: .65rem 1rem;
        color: var(--text-white);
        font-size: .9rem;
        width: 100%;
        transition: border-color .2s;
    }
    .form-mk:focus { outline: none; border-color: var(--accent); background: transparent; }
    .form-mk option { background: var(--bg-card); }
    .form-label-mk {
        display: block;
        font-size: .8rem;
        color: var(--text-muted);
        margin-bottom: .4rem;
        font-weight: 500;
    }
    .toggle-switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(255,255,255,0.1); border-radius: 24px; transition: .3s;
    }
    .toggle-slider:before {
        position: absolute; content: "";
        height: 18px; width: 18px; left: 3px; bottom: 3px;
        background: white; border-radius: 50%; transition: .3s;
    }
    .toggle-switch input:checked + .toggle-slider { background: var(--accent); }
    .toggle-switch input:checked + .toggle-slider:before { transform: translateX(20px); }
</style>
@endpush

@section('content')

<div style="max-width: 680px;">

    <a href="{{ route('admin.announcements.index') }}"
       style="display:inline-flex; align-items:center; gap:6px; color:var(--text-muted);
              font-size:.85rem; text-decoration:none; margin-bottom:1.25rem;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="content-card">
        <div class="content-card-header">
            <span style="font-weight:600; font-size:.95rem;">
                <i class="bi bi-pencil-square me-2" style="color:var(--accent);"></i>Edit Pengumuman
            </span>
        </div>
        <div class="content-card-body" style="padding: 1.5rem;">
            <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}">
                @csrf @method('PUT')

                {{-- Judul --}}
                <div style="margin-bottom:1.1rem;">
                    <label class="form-label-mk">Judul Pengumuman <span style="color:#f87171;">*</span></label>
                    <input type="text" name="title" class="form-mk"
                           value="{{ old('title', $announcement->title) }}"
                           required>
                    @error('title')
                        <div style="color:#f87171; font-size:.78rem; margin-top:.3rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Prioritas --}}
                <div style="margin-bottom:1.1rem;">
                    <label class="form-label-mk">Tingkat Prioritas <span style="color:#f87171;">*</span></label>
                    <select name="priority" class="form-mk" required>
                        @foreach(['normal' => 'Normal', 'penting' => 'Penting', 'urgent' => 'Urgent'] as $val => $label)
                        <option value="{{ $val }}" {{ old('priority', $announcement->priority) == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @error('priority')
                        <div style="color:#f87171; font-size:.78rem; margin-top:.3rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Isi --}}
                <div style="margin-bottom:1.1rem;">
                    <label class="form-label-mk">Isi Pengumuman <span style="color:#f87171;">*</span></label>
                    <textarea name="content" class="form-mk" rows="6"
                              required style="resize:vertical;">{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                        <div style="color:#f87171; font-size:.78rem; margin-top:.3rem;">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div style="margin-bottom:1.75rem; display:flex; align-items:center; gap:12px;">
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                    <div>
                        <div style="color:var(--text-white); font-size:.88rem; font-weight:500;">Aktifkan Pengumuman</div>
                        <div style="color:var(--text-muted); font-size:.75rem;">Jika aktif, pengumuman tampil ke seluruh penghuni</div>
                    </div>
                </div>

                <div style="display:flex; gap:10px;">
                    <button type="submit"
                            style="background:var(--accent); color:#fff; border:none;
                                   padding:10px 22px; border-radius:10px; font-size:.9rem;
                                   font-weight:600; cursor:pointer;">
                        <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.announcements.index') }}"
                       style="background:rgba(255,255,255,0.06); color:var(--text-muted);
                              border:1px solid rgba(255,255,255,0.1); padding:10px 20px;
                              border-radius:10px; font-size:.9rem; text-decoration:none;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
