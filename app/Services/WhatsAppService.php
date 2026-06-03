<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;
    protected string $url;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->url   = config('services.fonnte.url');
    }

    /**
     * Kirim pesan WhatsApp
     */
    public function send(string $phone, string $message): array
    {
        // Format nomor HP — pastikan pakai format internasional
        $phone = $this->formatPhone($phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->url, [
                'target'  => $phone,
                'message' => $message,
            ]);

            $result = $response->json();

            Log::info('WhatsApp sent', [
                'phone'   => $phone,
                'status'  => $result['status'] ?? 'unknown',
                'message' => $message,
            ]);

            return [
                'success' => $result['status'] ?? false,
                'message' => $result['reason'] ?? 'Pesan terkirim',
            ];

        } catch (\Exception $e) {
            Log::error('WhatsApp error', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Format nomor HP ke format internasional
     * 08xxx → 628xxx
     */
    protected function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // hapus non-digit

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}