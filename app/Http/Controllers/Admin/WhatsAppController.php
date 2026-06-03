<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Tenant;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected WhatsAppService $wa;

    public function __construct(WhatsAppService $wa)
    {
        $this->wa = $wa;
    }

    /**
     * Kirim reminder ke 1 tagihan
     */
    public function sendReminder(Payment $payment)
    {
        $payment->load(['tenant', 'room']);

        if (!$payment->tenant?->phone) {
            return back()->with('error', 'Nomor HP penghuni tidak ditemukan.');
        }

        $message = $this->buildReminderMessage($payment);
        $result  = $this->wa->send($payment->tenant->phone, $message);

        if ($result['success']) {
            return back()->with('success',
                "Reminder berhasil dikirim ke {$payment->tenant->name} ({$payment->tenant->phone}).");
        }

        return back()->with('error',
            "Gagal kirim ke {$payment->tenant->name}: {$result['message']}");
    }

    /**
     * Kirim reminder bulk ke semua tagihan unpaid/overdue
     */
    public function sendBulkReminder(Request $request)
    {
        $payments = Payment::with(['tenant', 'room'])
            ->whereIn('status', ['unpaid', 'overdue'])
            ->whereHas('tenant', fn($q) => $q->where('status', 'active'))
            ->get();

        if ($payments->isEmpty()) {
            return back()->with('error', 'Tidak ada tagihan yang perlu diingatkan.');
        }

        $success = 0;
        $failed  = 0;

        foreach ($payments as $payment) {
            if (!$payment->tenant?->phone) {
                $failed++;
                continue;
            }

            $message = $this->buildReminderMessage($payment);
            $result  = $this->wa->send($payment->tenant->phone, $message);

            $result['success'] ? $success++ : $failed++;

            // Delay kecil supaya tidak spam
            usleep(500000); // 0.5 detik
        }

        $msg = "Reminder terkirim: {$success} berhasil";
        if ($failed > 0) $msg .= ", {$failed} gagal";

        return back()->with('success', $msg);
    }

    /**
     * Kirim reminder ke penghuni tertentu (semua tagihan unpaid-nya)
     */
    public function sendTenantReminder(Tenant $tenant)
    {
        if (!$tenant->phone) {
            return back()->with('error', 'Nomor HP penghuni tidak ditemukan.');
        }

        $unpaidPayments = Payment::with('room')
            ->where('tenant_id', $tenant->id)
            ->whereIn('status', ['unpaid', 'overdue'])
            ->get();

        if ($unpaidPayments->isEmpty()) {
            return back()->with('error', "{$tenant->name} tidak memiliki tagihan yang belum dibayar.");
        }

        $message = $this->buildTenantReminderMessage($tenant, $unpaidPayments);
        $result  = $this->wa->send($tenant->phone, $message);

        if ($result['success']) {
            return back()->with('success',
                "Reminder berhasil dikirim ke {$tenant->name}.");
        }

        return back()->with('error',
            "Gagal kirim ke {$tenant->name}: {$result['message']}");
    }

    /**
     * Template pesan reminder per tagihan
     */
    protected function buildReminderMessage(Payment $payment): string
    {
        $name    = $payment->tenant->name;
        $room    = $payment->room->name ?? '-';
        $amount  = 'Rp ' . number_format($payment->amount, 0, ',', '.');
        $due     = $payment->due_date->format('d M Y');
        $status  = $payment->status === 'overdue' ? '⚠️ TERLAMBAT' : '🔔 Jatuh Tempo';

        return <<<MSG
        {$status} - Reminder Pembayaran Sewa

        Halo *{$name}*,

        Kami ingin mengingatkan bahwa tagihan sewa kamu belum dibayar:

        🏠 Kamar   : {$room}
        💰 Tagihan : {$amount}
        📅 Jatuh Tempo : {$due}

        Segera lakukan pembayaran dan kirim laporan melalui portal penghuni.

        Terima kasih 🙏
        _ManageMyKos_
        MSG;
    }

    /**
     * Template pesan reminder per penghuni (semua tagihan unpaid)
     */
    protected function buildTenantReminderMessage(Tenant $tenant, $payments): string
    {
        $name  = $tenant->name;
        $room  = $tenant->room->name ?? '-';
        $total = 'Rp ' . number_format($payments->sum('amount'), 0, ',', '.');
        $count = $payments->count();

        $detail = '';
        foreach ($payments as $p) {
            $detail .= "   • {$p->due_date->format('M Y')} — Rp " .
                number_format($p->amount, 0, ',', '.') . "\n";
        }

        return <<<MSG
        🔔 Reminder Pembayaran Sewa

        Halo *{$name}*,

        Kamu memiliki *{$count} tagihan* yang belum dibayar:

        🏠 Kamar : {$room}
        {$detail}
        💰 Total  : {$total}

        Segera lakukan pembayaran dan kirim laporan melalui portal penghuni.

        Terima kasih 🙏
        _ManageMyKos_
        MSG;
    }
    
    public function index()
    {
        $unpaidPayments = Payment::with(['tenant', 'room'])
            ->whereIn('status', ['unpaid', 'overdue'])
            ->whereHas('tenant', fn($q) => $q->where('status', 'active'))
            ->latest()
            ->get();

        $overdueCount = $unpaidPayments->where('status', 'overdue')->count();
        $unpaidCount  = $unpaidPayments->where('status', 'unpaid')->count();

        return view('admin.whatsapp.index', compact(
            'unpaidPayments', 'overdueCount', 'unpaidCount'
        ));
    }
}