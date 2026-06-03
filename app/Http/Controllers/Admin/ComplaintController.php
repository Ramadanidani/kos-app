<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['tenant', 'room'])->latest();

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $complaints    = $query->paginate(10);
        $totalPending  = Complaint::where('status', 'pending')->count();
        $totalProgress = Complaint::where('status', 'in_progress')->count();
        $totalResolved = Complaint::where('status', 'resolved')->count();

        return view('admin.complaints.index', compact(
            'complaints', 'totalPending', 'totalProgress', 'totalResolved'
        ));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['tenant', 'room']);
        return view('admin.complaints.show', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status'      => 'required|in:pending,in_progress,resolved',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $complaint->update($validated);

        return back()->with('success', 'Status keluhan berhasil diperbarui.');
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return redirect()->route('admin.complaints.index')
            ->with('success', 'Keluhan berhasil dihapus.');
    }
}