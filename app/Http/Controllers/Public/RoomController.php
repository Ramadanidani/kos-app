<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // Halaman daftar semua kamar
    public function index(Request $request)
    {
        $query = Room::with(['primaryPhoto']);

        // Filter status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter tipe
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $rooms = $query->orderBy('floor')->paginate(9);

        $totalRooms     = Room::count();
        $availableRooms = Room::available()->count();
        $occupiedRooms  = Room::where('status', 'occupied')->count();

        return view('public.rooms.index', compact(
            'rooms', 'totalRooms', 'availableRooms', 'occupiedRooms'
        ));
    }

    // Halaman detail kamar
    public function show(Room $room)
    {
        $room->load('photos');

        // Kamar lain yang tersedia (rekomendasi)
        $relatedRooms = Room::available()
            ->where('id', '!=', $room->id)
            ->where('type', $room->type)
            ->with('primaryPhoto')
            ->limit(3)
            ->get();

        return view('public.rooms.show', compact('room', 'relatedRooms'));
    }
}