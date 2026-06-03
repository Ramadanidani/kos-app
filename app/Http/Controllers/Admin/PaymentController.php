<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Tenant;
use App\Models\Room;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['tenant', 'room'])->latest();

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->month) {
            $query->whereMonth('due_date', date('m', strtotime($request->month)))
                  ->whereYear('due_date', date('Y', strtotime($request->month)));
        }

        $payments     = $query->paginate(10);
        $totalUnpaid  = Payment::where('status', 'unpaid')->sum('amount');
        $totalPaid    = Payment::where('status', 'paid')->sum('amount');
        $totalOverdue = Payment::where('status', 'overdue')->count();

        return view('admin.payments.index', compact(
            'payments', 'totalUnpaid', 'totalPaid', 'totalOverdue'
        ));
    }

    public function create()
    {
        $tenants = Tenant::with('room')->where('status', 'active')->orderBy('name')->get();
        return view('admin.payments.create', compact('tenants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'amount'    => 'required|numeric|min:0',
            'due_date'  => 'required|date',
            'status'    => 'required|in:unpaid,paid,overdue',
            'method'    => 'nullable|string|max:50',
            'paid_date' => 'nullable|date|required_if:status,paid',
            'notes'     => 'nullable|string',
        ]);

        $tenant = Tenant::findOrFail($validated['tenant_id']);

        Payment::create(array_merge($validated, [
            'room_id' => $tenant->room_id,
        ]));

        return redirect()->route('admin.payments.index')
            ->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['tenant', 'room']);
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $payment->load(['tenant', 'room']);
        return view('admin.payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount'    => 'required|numeric|min:0',
            'due_date'  => 'required|date',
            'status'    => 'required|in:unpaid,paid,overdue',
            'method'    => 'nullable|string|max:50',
            'paid_date' => 'nullable|date|required_if:status,paid',
            'notes'     => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }

    // Konfirmasi bayar langsung dari tabel
    public function confirm(Payment $payment)
    {
        $payment->update([
            'status'    => 'paid',
            'paid_date' => now()->toDateString(),
        ]);

        return back()->with('success', "Pembayaran {$payment->tenant->name} berhasil dikonfirmasi.");
    }

    // Generate tagihan bulanan untuk semua penghuni aktif
    public function generateMonthly(Request $request)
    {
        $request->validate(['month' => 'required|date_format:Y-m']);

        $tenants = Tenant::with('room')->where('status', 'active')->get();
        $count   = 0;

        foreach ($tenants as $tenant) {
            if (!$tenant->room_id) continue;

            // Cek sudah ada tagihan bulan ini belum
            $exists = Payment::where('tenant_id', $tenant->id)
                ->whereYear('due_date',  date('Y', strtotime($request->month . '-01')))
                ->whereMonth('due_date', date('m', strtotime($request->month . '-01')))
                ->exists();

            if (!$exists) {
                Payment::create([
                    'tenant_id' => $tenant->id,
                    'room_id'   => $tenant->room_id,
                    'amount'    => $tenant->room->price,
                    'due_date'  => $request->month . '-01',
                    'status'    => 'unpaid',
                ]);
                $count++;
            }
        }

        return back()->with('success', "{$count} tagihan bulanan berhasil digenerate.");
    }
}