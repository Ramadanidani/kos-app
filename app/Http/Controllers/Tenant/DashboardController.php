<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = Auth::guard('tenant')->user()
            ->load([
                'room',
                'payments' => fn($q) => $q->latest()->take(5),
                'complaints' => fn($q) => $q->latest()->take(3),
            ]);

        $unpaidCount  = $tenant->payments->where('status', 'unpaid')->count();
        $unpaidTotal  = $tenant->payments->where('status', 'unpaid')->sum('amount');
        $paidTotal    = $tenant->payments->where('status', 'paid')->sum('amount');

        return view('tenant.dashboard', compact(
            'tenant', 'unpaidCount', 'unpaidTotal', 'paidTotal'
        ));
    }
}