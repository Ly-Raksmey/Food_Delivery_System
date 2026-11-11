<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token', '');
    }

    public function sendMessage(string $chatId, string $text, ?string $parseMode = 'HTML'): bool
    {
        if (empty($this->botToken)) {
            Log::error('Telegram bot token not configured');
            return false;
        }

        if (empty($chatId)) {
            Log::error('Telegram chat ID not configured');
            return false;
        }

        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";

        try {
            $payload = [
                'chat_id' => $chatId,
                'text' => $text,
            ];
            
            if ($parseMode) {
                $payload['parse_mode'] = $parseMode;
            }

            $response = Http::post($url, $payload)->throw()->json();

            if (isset($response['ok']) && $response['ok'] === true) {
                Log::info('Telegram message sent successfully', ['chat_id' => $chatId]);
                return true;
            }

            Log::error('Telegram API returned error', [
                'chat_id' => $chatId,
                'response' => $response
            ]);
            return false;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Telegram connection failed', [
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Telegram send failed', [
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function getBotInfo(): ?array
    {
        if (empty($this->botToken)) {
            return null;
        }

        $url = "https://api.telegram.org/bot{$this->botToken}/getMe";

        try {
            $response = Http::post($url)->throw()->json();
            return $response['ok'] ? ($response['result'] ?? null) : null;
        } catch (\Exception $e) {
            Log::error('Failed to get bot info', ['error' => $e->getMessage()]);
            return null;
        }
    }
}