<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $tenantId = Auth::guard('tenant')->id();

        $announcements = Announcement::where('is_active', true)
            ->with(['reactions'])
            ->latest()
            ->paginate(10);

        // Attach tenant's own reaction for each announcement
        $announcements->each(function ($announcement) use ($tenantId) {
            $announcement->my_reaction = $announcement->reactions
                ->where('tenant_id', $tenantId)
                ->first()?->reaction;

            $announcement->reaction_counts = $announcement->reactions
                ->groupBy('reaction')
                ->map(fn($group) => $group->count());
        });

        return view('tenant.announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement)
    {
        abort_if(!$announcement->is_active, 404);

        $tenantId = Auth::guard('tenant')->id();

        $reactionCounts = $announcement->reactions()
            ->selectRaw('reaction, count(*) as total')
            ->groupBy('reaction')
            ->pluck('total', 'reaction');

        $myReaction = AnnouncementReaction::where('announcement_id', $announcement->id)
            ->where('tenant_id', $tenantId)
            ->first()?->reaction;

        return view('tenant.announcements.show', compact('announcement', 'reactionCounts', 'myReaction'));
    }

    public function react(Request $request, Announcement $announcement)
    {
        abort_if(!$announcement->is_active, 403);

        $request->validate([
            'reaction' => 'required|in:👍,❤️,😮,😢,👏',
        ]);

        $tenantId = Auth::guard('tenant')->id();

        $existing = AnnouncementReaction::where('announcement_id', $announcement->id)
            ->where('tenant_id', $tenantId)
            ->first();

        if ($existing) {
            if ($existing->reaction === $request->reaction) {
                // Toggle off — hapus reaksi jika sama
                $existing->delete();
                $myReaction = null;
            } else {
                // Ganti reaksi
                $existing->update(['reaction' => $request->reaction]);
                $myReaction = $request->reaction;
            }
        } else {
            AnnouncementReaction::create([
                'announcement_id' => $announcement->id,
                'tenant_id'       => $tenantId,
                'reaction'        => $request->reaction,
            ]);
            $myReaction = $request->reaction;
        }

        $reactionCounts = $announcement->reactions()
            ->selectRaw('reaction, count(*) as total')
            ->groupBy('reaction')
            ->pluck('total', 'reaction');

        if ($request->expectsJson()) {
            return response()->json([
                'my_reaction'     => $myReaction,
                'reaction_counts' => $reactionCounts,
            ]);
        }

        return back()->with('success', 'Reaksi berhasil disimpan.');
    }
}
