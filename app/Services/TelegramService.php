<?php

namespace App\Services;

use Throwable;

class TelegramService
{
    private string $botToken;

    private string $chatId;

    public function __construct()
    {
        $this->botToken =
            (string) env('TELEGRAM_BOT_TOKEN', '');

        $this->chatId =
            (string) env('TELEGRAM_CHAT_ID', '');
    }

    public function sendMessage(string $message): bool
    {
        if (
            $this->botToken === ''
            || $this->chatId === ''
        ) {
            log_message(
                'error',
                'Telegram tidak dikirim: konfigurasi token/chat ID belum tersedia.'
            );

            return false;
        }

        try {
            $client = \Config\Services::curlrequest([
                'timeout' => 10,
                'connect_timeout' => 5,
            ]);

            $response = $client->post(
                'https://api.telegram.org/bot'
                    . $this->botToken
                    . '/sendMessage',
                [
                    'form_params' => [
                        'chat_id' => $this->chatId,
                        'text' => $message,
                    ],
                ]
            );

            $body = json_decode(
                (string) $response->getBody(),
                true
            );

            return
                $response->getStatusCode() >= 200
                && $response->getStatusCode() < 300
                && is_array($body)
                && ($body['ok'] ?? false) === true;
        } catch (Throwable $exception) {
            /*
             * Kegagalan Telegram tidak boleh menggagalkan
             * penyimpanan hasil pengukuran.
             */
            log_message(
                'error',
                'Telegram exception: '
                    . $exception->getMessage()
            );

            return false;
        }
    }
}
