<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentReport;
use Illuminate\Http\Request;

class PaymentReportController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentReport::with(['tenant', 'room'])->latest();

        if ($request->period) {
            $query->where('period', $request->period);
        }

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $reports       = $query->paginate(10);
        $totalPending  = PaymentReport::where('status', 'pending')->count();
        $totalVerified = PaymentReport::where('status', 'verified')->count();

        // Daftar periode yang ada untuk filter
        $periods = PaymentReport::selectRaw('DISTINCT period')
            ->orderBy('period', 'desc')
            ->pluck('period');

        return view('admin.payment-reports.index', compact(
            'reports', 'totalPending', 'totalVerified', 'periods'
        ));
    }

    public function show(PaymentReport $paymentReport)
    {
        $paymentReport->load(['tenant', 'room']);
        return view('admin.payment-reports.show', compact('paymentReport'));
    }

    public function verify(PaymentReport $paymentReport)
    {
        $paymentReport->update(['status' => 'verified']);

        return back()->with('success',
            "Laporan dari {$paymentReport->tenant->name} berhasil diverifikasi.");
    }
}