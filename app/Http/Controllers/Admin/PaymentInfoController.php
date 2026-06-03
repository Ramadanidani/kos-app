<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentInfoController extends Controller
{
    public function edit()
    {
        $info = PaymentInfo::first() ?? new PaymentInfo();
        return view('admin.payment-info.edit', compact('info'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'bank_name'      => 'nullable|string|max:50',
            'account_number' => 'nullable|string|max:30',
            'account_name'   => 'nullable|string|max:100',
            'whatsapp'       => 'nullable|string|max:20',
            'notes'          => 'nullable|string|max:500',
            'qris_image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $info = PaymentInfo::first() ?? new PaymentInfo();

        // Upload QRIS
        if ($request->hasFile('qris_image')) {
            // Hapus foto lama
            if ($info->qris_image) {
                Storage::disk('public')->delete($info->qris_image);
            }
            $validated['qris_image'] = $request->file('qris_image')
                ->store('payment', 'public');
        }

        $info->fill($validated)->save();

        return back()->with('success', 'Informasi pembayaran berhasil diperbarui.');
    }
}