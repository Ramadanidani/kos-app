@extends('layouts.tenant')

@section('title', 'Ajukan Pindah Kamar')
@section('page-title', 'Ajukan Pindah Kamar')

@push('styles')
<style>
    .form-mk { background:transparent; border:1px solid rgba(255,255,255,0.12); border-radius:10px; padding:.7rem 1rem; color:var(--text-white); font-size:.9rem; width:100%; transition:border-color .2s; }
    .form-mk:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(255,140,50,0.15); background:transparent; }
    .form-mk option { background:var(--bg-card); }
    .form-mk::placeholder { color:rgba(255,255,255,0.2); }
    .label-mk { color:var(--text-muted); font-size:.82rem; margin-bottom:6px; display:block; }
    .room-option-card { background:transparent; border:1px solid rgba(255,255,255,0.08); border-radius:12px; padding:1rem; cursor:pointer; transition:.2s; }
    .room-option-card:hover, .room-option-card.selected { border-color:var(--accent); background:rgba(255,140,50,0.06); }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('tenant.transfers.index') }}"
       style="color:var(--text-muted); text-decoration:none; font-size:.88rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <span style="color:rgba(255,255,255,0.2);">/</span>
    <span style="color:var(--text-white); font-size:.88rem;">Ajukan Pindah Kamar</span>
</div>

{{-- Alert pending --}}
@if($hasPending)
<div style="background:rgba(234,179,8,0.1); border:1px solid rgba(234,179,8,0.2);
            border-radius:12px; padding:1rem 1.25rem; margin-bottom:1.5rem;
            display:flex; align-items:center; gap:12px;">
    <i class="bi bi-hourglass-split" style="color:#fbbf24; font-size:1.2rem; flex-shrink:0;"></i>
    <div>
        <div style="color:#fbbf24; font-weight:600; font-size:.9rem;">
            Kamu masih memiliki pengajuan yang sedang diproses
        </div>
        <div style="color:var(--text-muted); font-size:.8rem; margin-top:2px;">
            Tunggu hingga pengajuan sebelumnya disetujui atau ditolak.
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div style="background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.2);
            border-radius:10px; padding:12px 16px; margin-bottom:1.25rem;
            color:#f87171; font-size:.85rem;">
    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.07);
                    border-radius:14px; padding:1.5rem;">

            {{-- Kamar saat ini --}}
            <div style="background:rgba(255,255,255,0.04); border-radius:10px;
                        padding:.85rem 1rem; margin-bottom:1.5rem;
                        display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; background:rgba(239,68,68,0.12);
                            border-radius:10px; display:flex; align-items:center;
                            justify-content:center; flex-shrink:0;">
                    <i class="bi bi-door-closed-fill" style="color:#f87171;"></i>
                </div>
                <div>
                    <div style="color:var(--text-muted); font-size:.75rem;">Kamar Saat Ini</div>
                    <div style="color:var(--text-white); font-weight:600;">
                        {{ $tenant->room->name ?? '—' }}
                        @if($tenant->room)
                            <span style="color:var(--text-muted); font-weight:400; font-size:.82rem;">
                                — {{ $tenant->room->type }}, Lantai {{ $tenant->room->floor }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            @if(!$hasPending)
            <form method="POST" action="{{ route('tenant.transfers.store') }}">
                @csrf

                <div style="margin-bottom:1.25rem;">
                    <label class="label-mk">
                        Pilih Kamar Tujuan <span style="color:#f87171;">*</span>
                    </label>

                    @if($availableRooms->isEmpty())
                    <div style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2);
                                border-radius:10px; padding:1rem; color:#f87171; font-size:.85rem;">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Tidak ada kamar lain yang tersedia saat ini.
                    </div>
                    @else
                    <div class="row g-2" id="roomOptions">
                        @foreach($availableRooms as $room)
                        <div class="col-md-6">
                            <label>
                                <input type="radio" name="to_room_id" value="{{ $room->id }}"
                                       style="display:none;"
                                       onchange="selectRoom(this, {{ $room->id }})">
                                <div class="room-option-card" id="room_card_{{ $room->id }}">
                                    <div style="display:flex; justify-content:space-between; align-items:start;">
                                        <div>
                                            <div style="color:var(--text-white); font-weight:600; font-size:.9rem;">
                                                {{ $room->name }}
                                            </div>
                                            <div style="color:var(--text-muted); font-size:.75rem; margin-top:2px;">
                                                {{ $room->type }} · Lantai {{ $room->floor }} · {{ $room->size }}m²
                                            </div>
                                        </div>
                                        <div style="color:var(--accent); font-weight:700; font-size:.88rem; white-space:nowrap;">
                                            Rp {{ number_format($room->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    @if($room->facilities && count($room->facilities) > 0)
                                    <div style="margin-top:8px; display:flex; flex-wrap:wrap; gap:4px;">
                                        @foreach(array_slice($room->facilities, 0, 3) as $f)
                                        <span style="font-size:.68rem; padding:2px 7px;
                                                     background:rgba(255,255,255,0.05);
                                                     border-radius:20px; color:var(--text-muted);">
                                            {{ $f }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('to_room_id')
                        <div style="color:#f87171; font-size:.78rem; margin-top:6px;">{{ $message }}</div>
                    @enderror
                    @endif
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label class="label-mk">
                        Alasan Pindah Kamar <span style="color:#f87171;">*</span>
                    </label>
                    <textarea name="reason" class="form-mk" rows="4"
                              placeholder="Jelaskan alasan kamu ingin pindah kamar..."
                              maxlength="500" required>{{ old('reason') }}</textarea>
                </div>

                @if(!$availableRooms->isEmpty())
                <button type="submit"
                        style="background:var(--accent); color:#fff; border:none;
                               border-radius:10px; padding:12px 28px; font-weight:600;
                               cursor:pointer; font-size:.95rem; display:inline-flex;
                               align-items:center; gap:8px; transition:opacity .2s;"
                        onmouseover="this.style.opacity='.85'"
                        onmouseout="this.style.opacity='1'">
                    <i class="bi bi-send-fill"></i> Kirim Pengajuan
                </button>
                @endif

            </form>
            @endif

        </div>
    </div>

    <div class="col-lg-4">
        <div style="background:var(--bg-card); border:1px solid rgba(255,255,255,0.07);
                    border-radius:14px; padding:1.25rem;">
            <h6 style="color:var(--text-white); font-weight:600; margin-bottom:.85rem;">
                <i class="bi bi-info-circle me-2" style="color:var(--accent);"></i>
                Ketentuan
            </h6>
            <div style="font-size:.82rem; color:var(--text-muted); line-height:1.9;">
                <div class="d-flex gap-2 mb-2">
                    <i class="bi bi-check-circle-fill" style="color:var(--accent); flex-shrink:0;"></i>
                    Pengajuan akan direview oleh admin.
                </div>
                <div class="d-flex gap-2 mb-2">
                    <i class="bi bi-check-circle-fill" style="color:var(--accent); flex-shrink:0;"></i>
                    Kamar tujuan harus dalam status tersedia.
                </div>
                <div class="d-flex gap-2 mb-2">
                    <i class="bi bi-check-circle-fill" style="color:var(--accent); flex-shrink:0;"></i>
                    Hanya 1 pengajuan aktif dalam satu waktu.
                </div>
                <div class="d-flex gap-2">
                    <i class="bi bi-check-circle-fill" style="color:var(--accent); flex-shrink:0;"></i>
                    Jika disetujui, kamar otomatis berpindah.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function selectRoom(input, id) {
        document.querySelectorAll('.room-option-card').forEach(card => {
            card.classList.remove('selected');
        });
        document.getElementById('room_card_' + id).classList.add('selected');
    }
</script>
@endpush