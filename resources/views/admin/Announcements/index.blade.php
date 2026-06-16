@extends('layouts.admin')

@section('title', 'Pengumuman')
@section('page-title', 'Manajemen Pengumuman')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <p style="color:var(--text-muted); margin:0; font-size:.88rem;">
        Total {{ $announcements->total() }} pengumuman
    </p>
    <a href="{{ route('admin.announcements.create') }}"
       style="background:var(--accent); color:#fff; text-decoration:none;
              padding:9px 18px; border-radius:10px; font-size:.88rem;
              font-weight:600; display:inline-flex; align-items:center; gap:8px;">
        <i class="bi bi-plus-lg"></i> Buat Pengumuman
    </a>
</div>

<div class="content-card">
    @forelse($announcements as $ann)
    <div class="list-item-mk" style="padding: 1rem 1.25rem; flex-direction: column; gap: 8px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; width:100%; gap:12px;">
            <div style="flex:1; min-width:0;">
                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:4px;">
                    {{-- Badge Priority --}}
                    @php
                        $pMap = [
                            'normal'  => ['Normal',  'background:rgba(100,116,139,0.2); color:#94a3b8;'],
                            'penting' => ['Penting', 'background:rgba(234,179,8,0.15); color:#fbbf24;'],
                            'urgent'  => ['Urgent',  'background:rgba(239,68,68,0.15); color:#f87171;'],
                        ];
                        [$pLabel, $pStyle] = $pMap[$ann->priority] ?? ['Normal',''];
                    @endphp
                    <span style="{{ $pStyle }} font-size:.7rem; padding:3px 10px; border-radius:20px; font-weight:600;">
                        {{ $pLabel }}
                    </span>

                    {{-- Badge Status --}}
                    @if($ann->is_active)
                        <span style="background:rgba(34,197,94,0.12); color:#4ade80; font-size:.7rem; padding:3px 10px; border-radius:20px;">
                            <i class="bi bi-check-circle-fill me-1"></i>Aktif
                        </span>
                    @else
                        <span style="background:rgba(100,116,139,0.15); color:#94a3b8; font-size:.7rem; padding:3px 10px; border-radius:20px;">
                            <i class="bi bi-eye-slash me-1"></i>Nonaktif
                        </span>
                    @endif
                </div>

                <div style="color:var(--text-white); font-weight:600; font-size:.95rem; margin-bottom:4px;">
                    {{ $ann->title }}
                </div>
                <div style="color:var(--text-muted); font-size:.82rem; margin-bottom:6px;">
                    {{ Str::limit($ann->content, 100) }}
                </div>
                <div style="color:var(--text-muted); font-size:.75rem; display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                    <span><i class="bi bi-person-fill me-1"></i>{{ $ann->user->name }}</span>
                    <span><i class="bi bi-clock me-1"></i>{{ $ann->created_at->format('d M Y, H:i') }}</span>
                    <span><i class="bi bi-emoji-smile me-1"></i>{{ $ann->reactions->count() }} reaksi</span>
                </div>
            </div>

            <div style="display:flex; gap:6px; flex-shrink:0;">
                <a href="{{ route('admin.announcements.show', $ann) }}"
                   style="background:rgba(59,130,246,0.1); color:#60a5fa; border:1px solid rgba(59,130,246,0.2);
                          padding:6px 12px; border-radius:8px; font-size:.8rem; text-decoration:none;">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('admin.announcements.edit', $ann) }}"
                   style="background:rgba(255,140,50,0.1); color:var(--accent); border:1px solid rgba(255,140,50,0.2);
                          padding:6px 12px; border-radius:8px; font-size:.8rem; text-decoration:none;">
                    <i class="bi bi-pencil"></i>
                </a>
                <form method="POST" action="{{ route('admin.announcements.destroy', $ann) }}"
                      onsubmit="return confirm('Hapus pengumuman ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            style="background:rgba(239,68,68,0.1); color:#f87171; border:1px solid rgba(239,68,68,0.2);
                                   padding:6px 12px; border-radius:8px; font-size:.8rem; cursor:pointer;">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div style="padding:2.5rem; text-align:center; color:var(--text-muted);">
        <i class="bi bi-megaphone" style="font-size:2.5rem; opacity:.4; display:block; margin-bottom:.75rem;"></i>
        Belum ada pengumuman. <a href="{{ route('admin.announcements.create') }}" class="link-accent">Buat sekarang</a>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($announcements->hasPages())
<div style="margin-top:1.25rem;">
    {{ $announcements->links() }}
</div>
@endif

@endsection
