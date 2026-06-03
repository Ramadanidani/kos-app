<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function index()
    {
        $tenant     = Auth::guard('tenant')->user();
        $complaints = Complaint::where('tenant_id', $tenant->id)
            ->latest()->paginate(10);

        $unpaidCount = $this->getUnpaidCount();

        return view('tenant.complaints.index', compact('complaints', 'unpaidCount'));
    }

    public function create()
    {
        $unpaidCount = $this->getUnpaidCount();
        return view('tenant.complaints.create', compact('unpaidCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:100',
            'description' => 'required|string|max:1000',
        ]);

        $tenant = Auth::guard('tenant')->user();

        Complaint::create([
            'tenant_id'   => $tenant->id,
            'room_id'     => $tenant->room_id,
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'status'      => 'pending',
        ]);

        return redirect()->route('tenant.complaints.index')
            ->with('success', 'Keluhan berhasil dikirim! Admin akan segera menindaklanjuti.');
    }

    public function show(Complaint $complaint)
    {
        $tenant = Auth::guard('tenant')->user();

        // Pastikan hanya bisa lihat keluhan milik sendiri
        if ($complaint->tenant_id !== $tenant->id) {
            abort(403);
        }

        $unpaidCount = $this->getUnpaidCount();
        return view('tenant.complaints.show', compact('complaint', 'unpaidCount'));
    }

    private function getUnpaidCount()
    {
        return \App\Models\Payment::where('tenant_id', Auth::guard('tenant')->id())
            ->where('status', 'unpaid')->count();
    }
}