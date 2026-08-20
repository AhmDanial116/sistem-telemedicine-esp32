<?php

namespace App\Controllers;

use App\Services\TelegramService;

class TelegramTestController extends BaseController
{
    public function index()
    {
        $telegram = new TelegramService();

        $message =
            "🧪 TEST NOTIFIKASI TELEMEDICINE\n\n"
            . "Integrasi CodeIgniter → Railway → Telegram Bot berhasil.\n\n"
            . "Pesan ini hanya untuk pengujian fitur notifikasi.";

        $sent = $telegram->sendMessage($message);

        if ($sent) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Pesan Telegram berhasil dikirim.',
            ]);
        }

        return $this->response
            ->setStatusCode(500)
            ->setJSON([
                'success' => false,
                'message' => 'Pesan Telegram gagal dikirim.',
            ]);
    }
}
