@extends('layouts.admin')

@section('title', 'Penghuni')
@section('page-title', 'Penghuni')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <p style="color:var(--text-muted); margin:0; font-size:.88rem;">
        Total {{ $tenants->total() }} penghuni terdaftar.
    </p>
    <a href="{{ route('admin.tenants.create') }}"
       style="background:var(--accent); color:#fff; text-decoration:none;
              padding:9px 18px; border-radius:10px; font-size:.88rem;
              font-weight:600; display:inline-flex; align-items:center; gap:8px;">
        <i class="bi bi-plus-lg"></i> Tambah Penghuni
    </a>
</div>

@php
    $expiringSoon = $tenants->filter(function($t) {
        if (!$t->end_date || $t->status !== 'active') return false;
        $days = now()->diffInDays($t->end_date, false);
        return $days <= 7;
    });
@endphp

@if($expiringSoon->count() > 0)
<div style="background:rgba(234,179,8,0.1); border:1px solid rgba(234,179,8,0.2);
            border-radius:12px; padding:.85rem 1.1rem; margin-bottom:1.25rem;
            display:flex; align-items:center; gap:12px;">
    <i class="bi bi-exclamation-triangle-fill" style="color:#fbbf24; font-size:1.1rem; flex-shrink:0;"></i>
    <div style="font-size:.85rem; color:var(--text-muted);">
        <strong style="color:#fbbf24;">{{ $expiringSoon->count() }} penghuni</strong>
        memiliki sewa yang akan/sudah berakhir dalam 7 hari. Segera tindak lanjuti perpanjangan atau pengosongan kamar.
    </div>
</div>
@endif

<div class="content-card">
    <table class="table-mk">
        <thead>
            <tr>
                <th>Penghuni</th>
                <th>No. HP</th>
                <th>Kamar</th>
                <th>Mulai Sewa</th>
                <th>Akhir Sewa</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tenants as $tenant)
            <tr>
                {{-- Nama --}}
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:36px; height:36px; background:rgba(255,140,50,0.15);
                                    border-radius:50%; display:flex; align-items:center;
                                    justify-content:center; color:var(--accent);
                                    font-weight:700; font-size:.85rem; flex-shrink:0;">
                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:600; color:var(--text-white);">
                                {{ $tenant->name }}
                            </div>
                            @if($tenant->id_card)
                            <div style="font-size:.72rem; color:var(--text-muted);">
                                KTP: {{ $tenant->id_card }}
                            </div>
                            @endif
                        </div>
                    </div>
                </td>

                {{-- No HP --}}
                <td style="color:var(--text-muted); font-size:.88rem;">
                    <i class="bi bi-phone me-1"></i>{{ $tenant->phone }}
                </td>

                {{-- Kamar --}}
                <td>
                    @if($tenant->room)
                        <span style="background:rgba(255,140,50,0.1); color:var(--accent);
                                     padding:3px 10px; border-radius:20px; font-size:.8rem;
                                     border:1px solid rgba(255,140,50,0.2);">
                            {{ $tenant->room->name }}
                        </span>
                    @else
                        <span style="color:var(--text-muted); font-size:.82rem;">—</span>
                    @endif
                </td>

                {{-- Tanggal Mulai --}}
                <td style="color:var(--text-muted); font-size:.85rem;">
                    {{ $tenant->start_date?->format('d M Y') ?? '—' }}
                </td>

                {{-- Akhir Sewa --}}
                <td style="font-size:.85rem;">
                    @if($tenant->end_date)
                        @php
                            $daysLeft = now()->diffInDays($tenant->end_date, false);
                        @endphp

                        @if($daysLeft < 0)
                            {{-- Sudah lewat --}}
                            <span style="color:#f87171; font-weight:600;">
                                {{ $tenant->end_date->format('d M Y') }}
                            </span>
                            <div style="font-size:.72rem; color:#f87171;">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                Lewat {{ abs($daysLeft) }} hari
                            </div>
                        @elseif($daysLeft <= 7)
                            {{-- Mendekati (≤7 hari) --}}
                            <span style="color:#fbbf24; font-weight:600;">
                                {{ $tenant->end_date->format('d M Y') }}
                            </span>
                            <div style="font-size:.72rem; color:#fbbf24;">
                                <i class="bi bi-clock-fill me-1"></i>
                                {{ $daysLeft }} hari lagi
                            </div>
                        @else
                            {{-- Masih aman --}}
                            <span style="color:var(--text-muted);">
                                {{ $tenant->end_date->format('d M Y') }}
                            </span>
                        @endif
                    @else
                        <span style="color:var(--text-muted);">—</span>
                        <div style="font-size:.72rem; color:var(--text-muted);">Belum ditentukan</div>
                    @endif
                </td>

                {{-- Status --}}
                <td>
                    @if($tenant->status === 'active')
                        <span style="background:rgba(34,197,94,0.15); color:#4ade80;
                                     font-size:.72rem; padding:3px 10px; border-radius:20px; font-weight:500;">
                            Aktif
                        </span>
                    @else
                        <span style="background:rgba(239,68,68,0.15); color:#f87171;
                                     font-size:.72rem; padding:3px 10px; border-radius:20px; font-weight:500;">
                            Tidak Aktif
                        </span>
                    @endif
                </td>

                {{-- Aksi --}}
                <td>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.tenants.show', $tenant) }}"
                           style="padding:5px 10px; background:rgba(255,255,255,0.06);
                                  color:var(--text-muted); border-radius:7px; font-size:.8rem;
                                  text-decoration:none; border:1px solid rgba(255,255,255,0.08);"
                           title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.tenants.edit', $tenant) }}"
                           style="padding:5px 10px; background:rgba(255,140,50,0.1);
                                  color:var(--accent); border-radius:7px; font-size:.8rem;
                                  text-decoration:none; border:1px solid rgba(255,140,50,0.2);"
                           title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}"
                              onsubmit="return confirm('Hapus penghuni {{ $tenant->name }}?')">
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
                <td colspan="6" style="text-align:center; padding:3rem; color:var(--text-muted);">
                    <i class="bi bi-people" style="font-size:2.5rem; display:block; margin-bottom:.5rem;"></i>
                    Belum ada penghuni.
                    <a href="{{ route('admin.tenants.create') }}" style="color:var(--accent);">
                        Tambah sekarang
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($tenants->hasPages())
    <div style="padding:1rem 1.25rem; border-top:1px solid rgba(255,255,255,0.07);">
        {{ $tenants->links() }}
    </div>
    @endif
</div>

@endsection