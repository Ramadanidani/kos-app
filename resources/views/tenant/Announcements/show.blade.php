@extends('layouts.tenant')

@section('title', $announcement->title)
@section('page-title', 'Pengumuman')

@push('styles')
<style>
    .reaction-btn {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 40px;
        padding: 8px 18px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all .18s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--text-muted);
    }
    .reaction-btn:hover {
        background: rgba(255,255,255,0.1);
        transform: scale(1.08);
    }
    .reaction-btn.active {
        background: rgba(255,140,50,0.15);
        border-color: rgba(255,140,50,0.35);
        color: var(--accent);
        transform: scale(1.08);
    }
    .reaction-count { font-size: .82rem; font-weight: 700; }
</style>
@endpush

@section('content')

<div style="max-width:700px;">

    <a href="{{ route('tenant.announcements.index') }}"
       style="display:inline-flex; align-items:center; gap:6px; color:var(--text-muted);
              font-size:.85rem; text-decoration:none; margin-bottom:1.25rem;">
        <i class="bi bi-arrow-left"></i> Kembali ke Pengumuman
    </a>

    <div class="content-card">
        <div class="content-card-body" style="padding:1.75rem;">

            {{-- Priority badge --}}
            @php
                $pMap = [
                    'normal'  => ['Normal',  'background:rgba(100,116,139,0.2); color:#94a3b8;'],
                    'penting' => ['Penting', 'background:rgba(234,179,8,0.15); color:#fbbf24;'],
                    'urgent'  => ['Urgent',  'background:rgba(239,68,68,0.15); color:#f87171;'],
                ];
                [$pLabel, $pStyle] = $pMap[$announcement->priority] ?? ['Normal',''];
            @endphp
            <div style="margin-bottom:.9rem;">
                <span style="{{ $pStyle }} font-size:.75rem; padding:4px 13px; border-radius:20px; font-weight:600;">
                    @if($announcement->priority === 'urgent') 🚨 @elseif($announcement->priority === 'penting') ⚠️ @endif
                    {{ $pLabel }}
                </span>
            </div>

            {{-- Title --}}
            <h1 style="color:var(--text-white); font-size:1.3rem; font-weight:700; margin:0 0 .75rem; line-height:1.4;">
                {{ $announcement->title }}
            </h1>

            {{-- Meta --}}
            <div style="color:var(--text-muted); font-size:.8rem; display:flex; gap:16px; flex-wrap:wrap; margin-bottom:1.5rem;">
                <span><i class="bi bi-calendar3 me-1"></i>{{ $announcement->created_at->format('d M Y, H:i') }}</span>
                <span><i class="bi bi-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}</span>
            </div>

            {{-- Divider --}}
            <hr style="border-color:rgba(255,255,255,0.07); margin-bottom:1.5rem;">

            {{-- Content --}}
            <div style="color:var(--text-white); font-size:.95rem; line-height:1.8; white-space:pre-line; margin-bottom:2rem;">
                {{ $announcement->content }}
            </div>

            {{-- Divider --}}
            <hr style="border-color:rgba(255,255,255,0.07); margin-bottom:1.25rem;">

            {{-- Reaction section --}}
            <div>
                <div style="color:var(--text-muted); font-size:.8rem; margin-bottom:.85rem; font-weight:500; text-transform:uppercase; letter-spacing:.06em;">
                    <i class="bi bi-emoji-smile me-1"></i> Beri Reaksi
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;" id="reaction-wrapper">
                    @foreach(['👍','❤️','😮','😢','👏'] as $emoji)
                    @php $count = $reactionCounts[$emoji] ?? 0; @endphp
                    <button class="reaction-btn {{ $myReaction === $emoji ? 'active' : '' }}"
                            onclick="sendReaction('{{ $emoji }}', this)"
                            data-emoji="{{ $emoji }}">
                        <span style="font-size:1.3rem;">{{ $emoji }}</span>
                        <span class="reaction-count" id="count-{{ urlencode($emoji) }}">
                            {{ $count > 0 ? $count : '' }}
                        </span>
                    </button>
                    @endforeach
                </div>

                @if($myReaction)
                <div style="color:var(--text-muted); font-size:.78rem; margin-top:.75rem;" id="reaction-hint">
                    <i class="bi bi-check-circle me-1" style="color:#4ade80;"></i>
                    Kamu memberi reaksi {{ $myReaction }}. Klik lagi untuk menghapus.
                </div>
                @else
                <div style="color:var(--text-muted); font-size:.78rem; margin-top:.75rem;" id="reaction-hint">
                    Pilih reaksi untuk pengumuman ini.
                </div>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const csrfToken = '{{ csrf_token() }}';
const annId = {{ $announcement->id }};

async function sendReaction(emoji, btn) {
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

        const wrapper = document.getElementById('reaction-wrapper');
        wrapper.querySelectorAll('.reaction-btn').forEach(b => {
            b.classList.remove('active');
            const e = b.dataset.emoji;
            const countEl = document.getElementById('count-' + encodeURIComponent(e));
            if (countEl) {
                const c = data.reaction_counts[e] || 0;
                countEl.textContent = c > 0 ? c : '';
            }
        });

        const hint = document.getElementById('reaction-hint');
        if (data.my_reaction) {
            btn.classList.add('active');
            hint.innerHTML = `<i class="bi bi-check-circle me-1" style="color:#4ade80;"></i>Kamu memberi reaksi ${data.my_reaction}. Klik lagi untuk menghapus.`;
        } else {
            hint.innerHTML = 'Pilih reaksi untuk pengumuman ini.';
        }
    } catch (e) {
        console.error('Gagal mengirim reaksi:', e);
    }
}
</script>
@endpush
