<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Complaint;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRooms      = Room::count();
        $activeTenants   = Tenant::where('status', 'active')->count();
        $unpaidPayments  = Payment::where('status', 'unpaid')->orWhere('status', 'overdue')->get();
        $unpaidCount     = $unpaidPayments->count();
        $unpaidTotal     = $unpaidPayments->sum('amount');

        $pendingComplaints = Complaint::with(['tenant', 'room'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['tenant', 'room'])
            ->where('status', 'paid')
            ->latest('paid_date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRooms', 'activeTenants', 'unpaidCount',
            'unpaidTotal', 'pendingComplaints', 'recentPayments'
        ));
    }
}