<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomTransferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    public function index()
    {
        $tenant    = Auth::guard('tenant')->user();
        $transfers = RoomTransferRequest::where('tenant_id', $tenant->id)
            ->with(['fromRoom', 'toRoom'])
            ->latest()->paginate(10);

        $unpaidCount = $this->getUnpaidCount();
        return view('tenant.transfers.index', compact('transfers', 'unpaidCount'));
    }

    public function create()
    {
        $tenant      = Auth::guard('tenant')->user();
        $unpaidCount = $this->getUnpaidCount();

        // Cek apakah ada pengajuan pending
        $hasPending = RoomTransferRequest::where('tenant_id', $tenant->id)
            ->where('status', 'pending')->exists();

        // Kamar tersedia selain kamar saat ini
        $availableRooms = Room::where('status', 'available')
            ->where('id', '!=', $tenant->room_id)
            ->orderBy('name')->get();

        return view('tenant.transfers.create', compact(
            'tenant', 'availableRooms', 'hasPending', 'unpaidCount'
        ));
    }

    public function store(Request $request)
    {
        $tenant = Auth::guard('tenant')->user();

        // Cek ada pengajuan pending
        $hasPending = RoomTransferRequest::where('tenant_id', $tenant->id)
            ->where('status', 'pending')->exists();

        if ($hasPending) {
            return back()->with('error', 'Kamu masih memiliki pengajuan yang sedang diproses.');
        }

        $validated = $request->validate([
            'to_room_id' => 'required|exists:rooms,id',
            'reason'     => 'required|string|max:500',
        ]);

        // Cek kamar tujuan masih available
        $toRoom = Room::findOrFail($validated['to_room_id']);
        if ($toRoom->status !== 'available') {
            return back()->withErrors(['to_room_id' => 'Kamar tujuan sudah tidak tersedia.']);
        }

        RoomTransferRequest::create([
            'tenant_id'    => $tenant->id,
            'from_room_id' => $tenant->room_id,
            'to_room_id'   => $validated['to_room_id'],
            'reason'       => $validated['reason'],
            'status'       => 'pending',
        ]);

        return redirect()->route('tenant.transfers.index')
            ->with('success', 'Pengajuan pindah kamar berhasil dikirim! Tunggu persetujuan admin.');
    }

    private function getUnpaidCount()
    {
        return \App\Models\Payment::where('tenant_id', Auth::guard('tenant')->id())
            ->where('status', 'unpaid')->count();
    }
}