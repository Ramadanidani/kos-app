@extends('layouts.tenant')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@push('styles')
<style>
    .ann-card {
        background: var(--bg-card);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 14px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    .ann-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,.25);
    }
    .ann-card-urgent  { border-left: 4px solid #f87171; }
    .ann-card-penting { border-left: 4px solid #fbbf24; }
    .ann-card-normal  { border-left: 4px solid rgba(255,255,255,0.1); }

    .reaction-btn {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 30px;
        padding: 5px 12px;
        font-size: .9rem;
        cursor: pointer;
        transition: all .18s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: var(--text-muted);
    }
    .reaction-btn:hover {
        background: rgba(255,255,255,0.1);
        border-color: rgba(255,255,255,0.2);
    }
    .reaction-btn.active {
        background: rgba(255,140,50,0.15);
        border-color: rgba(255,140,50,0.3);
        color: var(--accent);
    }
    .reaction-count { font-size: .75rem; font-weight: 600; }
</style>
@endpush

@section('content')

<div style="margin-bottom:1rem;">
    <p style="color:var(--text-muted); font-size:.88rem; margin:0;">
        {{ $announcements->total() }} pengumuman aktif
    </p>
</div>

@forelse($announcements as $ann)
<div class="ann-card ann-card-{{ $ann->priority }}">
    <div style="padding:1.25rem 1.4rem;">

        {{-- Header --}}
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px; margin-bottom:.6rem;">
            <div style="flex:1; min-width:0;">
                @php
                    $pMap = [
                        'normal'  => ['Normal',  'background:rgba(100,116,139,0.2); color:#94a3b8;'],
                        'penting' => ['Penting', 'background:rgba(234,179,8,0.15); color:#fbbf24;'],
                        'urgent'  => ['Urgent',  'background:rgba(239,68,68,0.15); color:#f87171;'],
                    ];
                    [$pLabel, $pStyle] = $pMap[$ann->priority] ?? ['Normal',''];
                @endphp
                <span style="{{ $pStyle }} font-size:.68rem; padding:3px 9px; border-radius:20px; font-weight:600; margin-bottom:.5rem; display:inline-block;">
                    @if($ann->priority === 'urgent') 🚨 @elseif($ann->priority === 'penting') ⚠️ @endif
                    {{ $pLabel }}
                </span>
                <h3 style="color:var(--text-white); font-size:1rem; font-weight:700; margin:0 0 .3rem;">
                    {{ $ann->title }}
                </h3>
            </div>
            <a href="{{ route('tenant.announcements.show', $ann) }}"
               style="color:var(--accent); font-size:.78rem; text-decoration:none; flex-shrink:0;
                      white-space:nowrap; padding-top:2px;">
                Selengkapnya <i class="bi bi-chevron-right"></i>
            </a>
        </div>

        {{-- Preview konten --}}
        <p style="color:var(--text-muted); font-size:.86rem; margin:0 0 .85rem; line-height:1.6;">
            {{ Str::limit($ann->content, 160) }}
        </p>

        {{-- Meta --}}
        <div style="color:var(--text-muted); font-size:.75rem; margin-bottom:.9rem;">
            <i class="bi bi-clock me-1"></i>{{ $ann->created_at->diffForHumans() }}
        </div>

        {{-- Reaksi --}}
        <div style="display:flex; flex-wrap:wrap; gap:6px; align-items:center;"
             id="reactions-{{ $ann->id }}">
            @foreach(['👍','❤️','😮','😢','👏'] as $emoji)
            @php $count = $ann->reaction_counts[$emoji] ?? 0; @endphp
            <button class="reaction-btn {{ $ann->my_reaction === $emoji ? 'active' : '' }}"
                    onclick="sendReaction({{ $ann->id }}, '{{ $emoji }}', this)"
                    data-emoji="{{ $emoji }}">
                <span>{{ $emoji }}</span>
                <span class="reaction-count" id="count-{{ $ann->id }}-{{ urlencode($emoji) }}">
                    {{ $count > 0 ? $count : '' }}
                </span>
            </button>
            @endforeach
        </div>

    </div>
</div>
@empty
<div class="content-card">
    <div style="padding:3rem; text-align:center; color:var(--text-muted);">
        <i class="bi bi-megaphone" style="font-size:2.5rem; opacity:.4; display:block; margin-bottom:.75rem;"></i>
        Belum ada pengumuman saat ini.
    </div>
</div>
@endforelse

@if($announcements->hasPages())
<div style="margin-top:1rem;">{{ $announcements->links() }}</div>
@endif

@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
    || '{{ csrf_token() }}';

async function sendReaction(annId, emoji, btn) {
    const wrapper = document.getElementById('reactions-' + annId);
    const allBtns = wrapper.querySelectorAll('.reaction-btn');
    const isActive = btn.classList.contains('active');

    try {
        const resp = await fetch(`/penghuni/pengumuman/${annId}/reaksi`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ reaction: emoji }),
        });

        const data = await resp.json();

        // Update semua tombol
        allBtns.forEach(b => {
            b.classList.remove('active');
            const e = b.dataset.emoji;
            const key = encodeURIComponent(e);
            const countEl = document.getElementById(`count-${annId}-${key}`);
            if (countEl) {
                const c = data.reaction_counts[e] || 0;
                countEl.textContent = c > 0 ? c : '';
            }
        });

        // Set active pada yang dipilih (jika tidak di-toggle off)
        if (data.my_reaction) {
            btn.classList.add('active');
        }
    } catch (e) {
        console.error('Gagal mengirim reaksi:', e);
    }
}
</script>
@endpush
