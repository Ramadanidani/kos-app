<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomTransferRequest;
use App\Models\Room;
use Illuminate\Http\Request;

class TransferRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = RoomTransferRequest::with(['tenant', 'fromRoom', 'toRoom'])->latest();

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $transfers     = $query->paginate(10);
        $totalPending  = RoomTransferRequest::where('status', 'pending')->count();
        $totalApproved = RoomTransferRequest::where('status', 'approved')->count();
        $totalRejected = RoomTransferRequest::where('status', 'rejected')->count();

        return view('admin.transfers.index', compact(
            'transfers', 'totalPending', 'totalApproved', 'totalRejected'
        ));
    }

    public function show(RoomTransferRequest $transfer)
    {
        $transfer->load(['tenant', 'fromRoom', 'toRoom']);
        return view('admin.transfers.show', compact('transfer'));
    }

    public function approve(RoomTransferRequest $transfer)
    {
        if ($transfer->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        // Cek kamar tujuan masih available
        $toRoom = Room::findOrFail($transfer->to_room_id);
        if ($toRoom->status !== 'available') {
            return back()->with('error', 'Kamar tujuan sudah tidak tersedia.');
        }

        // Bebaskan kamar lama
        Room::where('id', $transfer->from_room_id)
            ->update(['status' => 'available']);

        // Isi kamar baru
        $toRoom->update(['status' => 'occupied']);

        // Update kamar penghuni
        $transfer->tenant->update(['room_id' => $transfer->to_room_id]);

        // Update status pengajuan
        $transfer->update(['status' => 'approved']);

        return back()->with('success', "Pengajuan pindah kamar {$transfer->tenant->name} berhasil disetujui.");
    }

    public function reject(Request $request, RoomTransferRequest $transfer)
    {
        if ($transfer->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $transfer->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', "Pengajuan pindah kamar {$transfer->tenant->name} berhasil ditolak.");
    }

    public function destroy(RoomTransferRequest $transfer)
    {
        $transfer->delete();
        return redirect()->route('admin.transfers.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }
}