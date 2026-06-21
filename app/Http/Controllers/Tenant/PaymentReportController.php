<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PaymentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentReportController extends Controller
{
    public function index()
    {
        $tenant  = Auth::guard('tenant')->user();
        $reports = PaymentReport::where('tenant_id', $tenant->id)
            ->latest()->paginate(10);

        $unpaidCount = $this->getUnpaidCount();
        return view('tenant.payment-reports.index', compact('reports', 'unpaidCount'));
    }

    public function create()
    {
        $unpaidCount = $this->getUnpaidCount();
        return view('tenant.payment-reports.create', compact('unpaidCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'period'      => 'required|date_format:Y-m',
            'amount'      => 'required|numeric|min:1000',
            'method'      => 'required|string|max:50',
            'proof_image' => 'required|image|mimes:jpg,jpeg,png|max:3072',
            'notes'       => 'nullable|string|max:500',
        ]);

        $tenant = Auth::guard('tenant')->user();

        // Hanya cek laporan yang masih aktif (pending/verified)
        // Laporan yang ditolak tidak menghalangi pengiriman ulang
        $exists = PaymentReport::where('tenant_id', $tenant->id)
            ->where('period', $validated['period'])
            ->whereIn('status', ['pending', 'verified'])
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->withErrors(['period' => 'Kamu sudah pernah mengirim laporan untuk periode ini.']);
        }

        // Upload bukti
        $proofPath = $request->file('proof_image')->store('payment-proofs', 'public');

        PaymentReport::create([
            'tenant_id'   => $tenant->id,
            'room_id'     => $tenant->room_id,
            'period'      => $validated['period'],
            'amount'      => $validated['amount'],
            'method'      => $validated['method'],
            'proof_image' => $proofPath,
            'notes'       => $validated['notes'] ?? null,
            'status'      => 'pending',
        ]);

        return redirect()->route('tenant.payment-reports.index')
            ->with('success', 'Laporan pembayaran berhasil dikirim! Admin akan segera memverifikasi.');
    }

    public function show(PaymentReport $paymentReport)
    {
        $tenant = Auth::guard('tenant')->user();

        if ($paymentReport->tenant_id !== $tenant->id) {
            abort(403);
        }

        $unpaidCount = $this->getUnpaidCount();
        return view('tenant.payment-reports.show', compact('paymentReport', 'unpaidCount'));
    }

    private function getUnpaidCount()
    {
        return \App\Models\Payment::where('tenant_id', Auth::guard('tenant')->id())
            ->where('status', 'unpaid')->count();
    }
}