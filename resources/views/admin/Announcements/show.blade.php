@extends('layouts.admin')

@section('title', 'Detail Pengumuman')
@section('page-title', 'Detail Pengumuman')

@section('content')

<div style="max-width: 760px;">

    <a href="{{ route('admin.announcements.index') }}"
       style="display:inline-flex; align-items:center; gap:6px; color:var(--text-muted);
              font-size:.85rem; text-decoration:none; margin-bottom:1.25rem;">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    {{-- Header Card --}}
    <div class="content-card" style="margin-bottom:1.25rem;">
        <div class="content-card-body" style="padding:1.5rem;">
            {{-- Priority & Status badges --}}
            <div style="display:flex; gap:8px; margin-bottom:.85rem; flex-wrap:wrap;">
                @php
                    $pMap = [
                        'normal'  => ['Normal',  'background:rgba(100,116,139,0.2); color:#94a3b8;'],
                        'penting' => ['Penting', 'background:rgba(234,179,8,0.15); color:#fbbf24;'],
                        'urgent'  => ['Urgent',  'background:rgba(239,68,68,0.15); color:#f87171;'],
                    ];
                    [$pLabel, $pStyle] = $pMap[$announcement->priority] ?? ['Normal',''];
                @endphp
                <span style="{{ $pStyle }} font-size:.75rem; padding:4px 12px; border-radius:20px; font-weight:600;">
                    {{ $pLabel }}
                </span>
                @if($announcement->is_active)
                    <span style="background:rgba(34,197,94,0.12); color:#4ade80; font-size:.75rem; padding:4px 12px; border-radius:20px;">
                        <i class="bi bi-check-circle-fill me-1"></i>Aktif
                    </span>
                @else
                    <span style="background:rgba(100,116,139,0.15); color:#94a3b8; font-size:.75rem; padding:4px 12px; border-radius:20px;">
                        <i class="bi bi-eye-slash me-1"></i>Nonaktif
                    </span>
                @endif
            </div>

            <h2 style="color:var(--text-white); font-size:1.2rem; font-weight:700; margin-bottom:.75rem;">
                {{ $announcement->title }}
            </h2>

            <div style="color:var(--text-muted); font-size:.8rem; display:flex; gap:16px; flex-wrap:wrap; margin-bottom:1.25rem;">
                <span><i class="bi bi-person-fill me-1"></i>{{ $announcement->user->name }}</span>
                <span><i class="bi bi-calendar3 me-1"></i>{{ $announcement->created_at->format('d M Y, H:i') }}</span>
                @if($announcement->updated_at != $announcement->created_at)
                    <span><i class="bi bi-pencil me-1"></i>Diedit {{ $announcement->updated_at->diffForHumans() }}</span>
                @endif
            </div>

            <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07);
                        border-radius:10px; padding:1.25rem; color:var(--text-white); font-size:.92rem;
                        line-height:1.7; white-space:pre-line;">
                {{ $announcement->content }}
            </div>
        </div>

        <div class="content-card-header" style="border-top:1px solid rgba(255,255,255,0.07); border-bottom:none; justify-content:flex-end;">
            <div style="display:flex; gap:8px;">
                <a href="{{ route('admin.announcements.edit', $announcement) }}"
                   style="background:rgba(255,140,50,0.1); color:var(--accent); border:1px solid rgba(255,140,50,0.2);
                          padding:8px 16px; border-radius:8px; font-size:.85rem; text-decoration:none;">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}"
                      onsubmit="return confirm('Hapus pengumuman ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            style="background:rgba(239,68,68,0.1); color:#f87171; border:1px solid rgba(239,68,68,0.2);
                                   padding:8px 16px; border-radius:8px; font-size:.85rem; cursor:pointer;">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Reaksi Summary --}}
    <div class="content-card">
        <div class="content-card-header">
            <span style="font-weight:600; font-size:.92rem;">
                <i class="bi bi-emoji-smile me-2" style="color:var(--accent);"></i>
                Reaksi Penghuni ({{ $announcement->reactions->count() }})
            </span>
        </div>
        <div class="content-card-body">
            @if($reactionCounts->isEmpty())
                <div style="color:var(--text-muted); font-size:.88rem; text-align:center; padding:1.5rem 0;">
                    Belum ada reaksi dari penghuni.
                </div>
            @else
                {{-- Summary emoji counts --}}
                <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:1.25rem;">
                    @foreach($reactionCounts as $emoji => $count)
                    <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08);
                                border-radius:40px; padding:6px 16px; display:flex; align-items:center; gap:8px;">
                        <span style="font-size:1.3rem;">{{ $emoji }}</span>
                        <span style="color:var(--text-white); font-weight:600; font-size:.9rem;">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- List per tenant --}}
                <table class="table-mk">
                    <thead>
                        <tr>
                            <th>Penghuni</th>
                            <th>Kamar</th>
                            <th>Reaksi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($announcement->reactions as $reaction)
                        <tr>
                            <td>{{ $reaction->tenant->name ?? '-' }}</td>
                            <td style="color:var(--text-muted);">{{ $reaction->tenant->room->name ?? '-' }}</td>
                            <td style="font-size:1.4rem;">{{ $reaction->reaction }}</td>
                            <td style="color:var(--text-muted); font-size:.8rem;">{{ $reaction->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>
@endsection
