@extends('layouts.admin')

@section('title', 'Daftar Kamar')
@section('page-title', 'Daftar Kamar')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p style="color:var(--text-muted); margin:0; font-size:.88rem;">
            Total {{ $rooms->total() }} kamar terdaftar.
        </p>
    </div>
    <a href="{{ route('admin.rooms.create') }}"
       style="background:var(--accent); color:#fff; text-decoration:none;
              padding:9px 18px; border-radius:10px; font-size:.88rem;
              font-weight:600; display:inline-flex; align-items:center; gap:8px;">
        <i class="bi bi-plus-lg"></i> Tambah Kamar
    </a>
</div>

<div class="content-card">
    <table class="table-mk">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Kamar</th>
                <th>Tipe</th>
                <th>Lantai</th>
                <th>Harga</th>
                <th>Status</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rooms as $room)
            <tr>
                {{-- Foto --}}
                <td>
                    @if($room->primaryPhoto)
                        <img src="{{ asset('storage/' . $room->primaryPhoto->photo_path) }}"
                             style="width:56px; height:44px; object-fit:cover;
                                    border-radius:8px; display:block;">
                    @else
                        <div style="width:56px; height:44px; background:#1a2d40;
                                    border-radius:8px; display:flex; align-items:center;
                                    justify-content:center;">
                            <i class="bi bi-image" style="color:rgba(255,255,255,0.2);"></i>
                        </div>
                    @endif
                </td>

                {{-- Nama --}}
                <td>
                    <div style="font-weight:600;">{{ $room->name }}</div>
                    <div style="font-size:.75rem; color:var(--text-muted);">{{ $room->size }} m²</div>
                </td>

                <td>
                    <span style="font-size:.8rem; padding:3px 10px;
                                 background:rgba(255,140,50,0.1); color:var(--accent);
                                 border-radius:20px; border:1px solid rgba(255,140,50,0.2);">
                        {{ $room->type }}
                    </span>
                </td>

                <td style="color:var(--text-muted); font-size:.88rem;">
                    Lantai {{ $room->floor }}
                </td>

                <td style="color:var(--accent); font-weight:600; font-size:.88rem;">
                    Rp {{ number_format($room->price, 0, ',', '.') }}
                </td>

                {{-- Status --}}
                <td>
                    @php
                        $statusMap = [
                            'available'   => ['label' => 'Tersedia',    'class' => 'badge-available'],
                            'occupied'    => ['label' => 'Terisi',      'class' => 'badge-occupied'],
                            'maintenance' => ['label' => 'Maintenance', 'class' => 'badge-maintenance'],
                        ];
                        $s = $statusMap[$room->status] ?? ['label' => $room->status, 'class' => ''];
                    @endphp
                    <span class="{{ $s['class'] }}"
                          style="font-size:.72rem; padding:3px 9px;
                                 border-radius:20px; font-weight:500;">
                        {{ $s['label'] }}
                    </span>
                </td>

                {{-- Jumlah foto --}}
                <td style="color:var(--text-muted); font-size:.85rem;">
                    <i class="bi bi-images me-1"></i>{{ $room->photos_count }}
                </td>

                {{-- Aksi --}}
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.rooms.show', $room) }}"
                           style="padding:5px 10px; background:rgba(255,255,255,0.06);
                                  color:var(--text-muted); border-radius:7px; font-size:.8rem;
                                  text-decoration:none; border:1px solid rgba(255,255,255,0.08);"
                           title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.rooms.edit', $room) }}"
                           style="padding:5px 10px; background:rgba(255,140,50,0.1);
                                  color:var(--accent); border-radius:7px; font-size:.8rem;
                                  text-decoration:none; border:1px solid rgba(255,140,50,0.2);"
                           title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
                              onsubmit="return confirm('Hapus kamar {{ $room->name }}? Semua foto akan ikut terhapus.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="padding:5px 10px; background:rgba(239,68,68,0.1);
                                           color:#f87171; border-radius:7px; font-size:.8rem;
                                           border:1px solid rgba(239,68,68,0.2); cursor:pointer;"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:3rem; color:var(--text-muted);">
                    <i class="bi bi-inbox" style="font-size:2.5rem; display:block; margin-bottom:.5rem;"></i>
                    Belum ada kamar. <a href="{{ route('admin.rooms.create') }}"
                                        style="color:var(--accent);">Tambah sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($rooms->hasPages())
    <div style="padding:1rem 1.25rem; border-top:1px solid rgba(255,255,255,0.07);">
        {{ $rooms->links() }}
    </div>
    @endif
</div>

@endsection