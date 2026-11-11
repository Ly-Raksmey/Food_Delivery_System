<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TestTelegramCommand extends Command
{
    protected $signature = 'telegram:test {chatId? : Telegram chat ID to send test message to}';
    protected $description = 'Test Telegram bot integration';

    public function handle(TelegramService $telegram): int
    {
        $botInfo = $telegram->getBotInfo();
        
        if (!$botInfo) {
            $this->error('Failed to connect to Telegram. Check your bot token.');
            return 1;
        }

        $this->info("Bot connected: @{botInfo['username']} (ID: {botInfo['id']})");

        $chatId = $this->argument('chatId') ?? config('services.telegram.admin_chat_id');

        if (empty($chatId)) {
            $this->error('No chat ID provided. Pass it as argument or set TELEGRAM_ADMIN_CHAT_ID in .env');
            return 1;
        }

        $message = "Test message from Food Delivery System\n\nBot: @{botInfo['username']}\nTime: {now()}";
        $sent = $telegram->sendMessage($chatId, $message);

        if ($sent) {
            $this->info("Test message sent to chat ID: {$chatId}");
            return 0;
        }

        $this->error("Failed to send message to chat ID: {$chatId}");
        return 1;
    }
}