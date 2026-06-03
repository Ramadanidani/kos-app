<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('primaryPhoto')->withCount('photos')->latest()->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'type'         => 'required|in:Standard,Deluxe,VIP',
            'price'        => 'required|numeric|min:0',
            'status'       => 'required|in:available,occupied,maintenance',
            'size'         => 'required|integer|min:1',
            'floor'        => 'required|integer|min:1',
            'description'  => 'nullable|string',
            'facilities'   => 'nullable|array',
            'facilities.*' => 'string|max:50',
            'photos'       => 'nullable|array',
            'photos.*'     => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $room = Room::create([
            'name'        => $validated['name'],
            'type'        => $validated['type'],
            'price'       => $validated['price'],
            'status'      => $validated['status'],
            'size'        => $validated['size'],
            'floor'       => $validated['floor'],
            'description' => $validated['description'] ?? null,
            'facilities'  => $validated['facilities'] ?? [],
        ]);

        // Upload foto
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $i => $photo) {
                $path = $photo->store('rooms', 'public');
                RoomPhoto::create([
                    'room_id'    => $room->id,
                    'photo_path' => $path,
                    'is_primary' => $i === 0,
                ]);
            }
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', "Kamar {$room->name} berhasil ditambahkan.");
    }

    public function show(Room $room)
    {
        $room->load('photos');
        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $room->load('photos');
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'type'         => 'required|in:Standard,Deluxe,VIP',
            'price'        => 'required|numeric|min:0',
            'status'       => 'required|in:available,occupied,maintenance',
            'size'         => 'required|integer|min:1',
            'floor'        => 'required|integer|min:1',
            'description'  => 'nullable|string',
            'facilities'   => 'nullable|array',
            'facilities.*' => 'string|max:50',
            'photos'       => 'nullable|array',
            'photos.*'     => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'delete_photos' => 'nullable|array',
            'delete_photos.*' => 'integer|exists:room_photos,id',
        ]);

        $room->update([
            'name'        => $validated['name'],
            'type'        => $validated['type'],
            'price'       => $validated['price'],
            'status'      => $validated['status'],
            'size'        => $validated['size'],
            'floor'       => $validated['floor'],
            'description' => $validated['description'] ?? null,
            'facilities'  => $validated['facilities'] ?? [],
        ]);

        // Hapus foto yang dipilih
        if (!empty($validated['delete_photos'])) {
            $photosToDelete = RoomPhoto::whereIn('id', $validated['delete_photos'])
                ->where('room_id', $room->id)->get();
            foreach ($photosToDelete as $photo) {
                Storage::disk('public')->delete($photo->photo_path);
                $photo->delete();
            }
        }

        // Upload foto baru
        if ($request->hasFile('photos')) {
            $hasPrimary = $room->photos()->where('is_primary', true)->exists();
            foreach ($request->file('photos') as $i => $photo) {
                $path = $photo->store('rooms', 'public');
                RoomPhoto::create([
                    'room_id'    => $room->id,
                    'photo_path' => $path,
                    'is_primary' => !$hasPrimary && $i === 0,
                ]);
            }
        }

        // Pastikan ada primary photo
        if ($room->photos()->exists() && !$room->photos()->where('is_primary', true)->exists()) {
            $room->photos()->first()->update(['is_primary' => true]);
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', "Kamar {$room->name} berhasil diperbarui.");
    }

    public function destroy(Room $room)
    {
        // Hapus semua foto dari storage
        foreach ($room->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', "Kamar {$room->name} berhasil dihapus.");
    }

    // Set foto utama
    public function setPrimaryPhoto(Request $request, Room $room)
    {
        $request->validate(['photo_id' => 'required|exists:room_photos,id']);

        $room->photos()->update(['is_primary' => false]);
        $room->photos()->where('id', $request->photo_id)->update(['is_primary' => true]);

        return back()->with('success', 'Foto utama berhasil diubah.');
    }
}