<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentInfo;

class PaymentController extends Controller
{
    public function index()
    {
        $tenant      = Auth::guard('tenant')->user();
        $payments    = Payment::where('tenant_id', $tenant->id)
            ->latest('due_date')->paginate(10);

        $unpaidCount = Payment::where('tenant_id', $tenant->id)
            ->where('status', 'unpaid')->count();
        $unpaidTotal = Payment::where('tenant_id', $tenant->id)
            ->where('status', 'unpaid')->sum('amount');
        $paidTotal   = Payment::where('tenant_id', $tenant->id)
            ->where('status', 'paid')->sum('amount');

        $paymentInfo = PaymentInfo::first(); 

        return view('tenant.payments.index', compact(
            'payments', 'unpaidCount', 'unpaidTotal', 'paidTotal', 'paymentInfo'
        ));
    }
}