<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('room')->latest()->paginate(10);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'available')->orderBy('name')->get();
        return view('admin.tenants.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'phone'      => 'required|string|max:20|unique:tenants,phone',
            'id_card'    => 'nullable|string|max:20',
            'room_id'    => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after:start_date',
            'password'   => 'required|string|min:6',
            'notes'      => 'nullable|string',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        if ($room->status !== 'available') {
            return back()->withInput()
                ->withErrors(['room_id' => 'Kamar ini sudah tidak tersedia.']);
        }

        $tenant = Tenant::create([
            'name'                 => $validated['name'],
            'phone'                => $validated['phone'],
            'id_card'              => $validated['id_card'] ?? null,
            'room_id'              => $validated['room_id'],
            'start_date'           => $validated['start_date'],
            'end_date'             => $validated['end_date'] ?? null,
            'notes'                => $validated['notes'] ?? null,
            'password'             => Hash::make($validated['password']),
            'must_change_password' => true,
            'status'               => 'active',
        ]);

        $room->update(['status' => 'occupied']);

        return redirect()->route('admin.tenants.index')
            ->with('success', "Penghuni {$tenant->name} berhasil ditambahkan. Password: {$validated['password']}");
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['room', 'payments' => fn($q) => $q->latest()->take(5), 'complaints' => fn($q) => $q->latest()->take(5)]);
        return view('admin.tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        $rooms = Room::where(function($q) use ($tenant) {
            $q->where('status', 'available')
              ->orWhere('id', $tenant->room_id);
        })->orderBy('name')->get();

        return view('admin.tenants.edit', compact('tenant', 'rooms'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'phone'      => 'required|string|max:20|unique:tenants,phone,' . $tenant->id,
            'id_card'    => 'nullable|string|max:20',
            'room_id'    => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after:start_date',
            'status'     => 'required|in:active,inactive',
            'password'   => 'nullable|string|min:6', // opsional saat edit
            'notes'      => 'nullable|string',
        ]);

        $oldRoomId = $tenant->room_id;
        $newRoomId = $validated['room_id'];

        if ($oldRoomId != $newRoomId) {
            $newRoom = Room::findOrFail($newRoomId);
            if ($newRoom->status !== 'available') {
                return back()->withInput()
                    ->withErrors(['room_id' => 'Kamar ini sudah tidak tersedia.']);
            }
            if ($oldRoomId) {
                Room::where('id', $oldRoomId)->update(['status' => 'available']);
            }
            $newRoom->update(['status' => 'occupied']);
        }

        if ($validated['status'] === 'inactive' && $tenant->status === 'active') {
            Room::where('id', $newRoomId)->update(['status' => 'available']);
        }

        $updateData = [
            'name'       => $validated['name'],
            'phone'      => $validated['phone'],
            'id_card'    => $validated['id_card'] ?? null,
            'room_id'    => $validated['room_id'],
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'] ?? null,
            'status'     => $validated['status'],
            'notes'      => $validated['notes'] ?? null,
        ];

        // Reset password hanya jika diisi
        if (!empty($validated['password'])) {
            $updateData['password']             = Hash::make($validated['password']);
            $updateData['must_change_password'] = true;
        }

        $tenant->update($updateData);

        return redirect()->route('admin.tenants.index')
            ->with('success', "Data penghuni {$tenant->name} berhasil diperbarui.");
    }

    public function destroy(Tenant $tenant)
    {
        // Bebaskan kamar
        if ($tenant->room_id) {
            Room::where('id', $tenant->room_id)->update(['status' => 'available']);
        }

        $tenant->delete();

        return redirect()->route('admin.tenants.index')
            ->with('success', "Penghuni {$tenant->name} berhasil dihapus.");
    }
}