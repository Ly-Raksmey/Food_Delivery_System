<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class SendTelegramOrderNotification
{
    public function __construct(
        protected TelegramService $telegram
    ) {
    }

    public function handle(OrderPlaced $event): void
    {
        $order = $event->order->load('items', 'address', 'payment', 'user');

        $adminChatId = config('services.telegram.admin_chat_id');
        if (empty($adminChatId)) {
            Log::warning('Telegram admin chat ID not configured');
            return;
        }

        $message = $this->formatOrderMessage($order);
        $this->telegram->sendMessage($adminChatId, $message);
    }

    protected function formatOrderMessage($order): string
    {
        $items = [];
        foreach ($order->items as $item) {
            $items[] = "  • {$item->food_name} x{$item->quantity} - $" . number_format($item->subtotal, 2);
        }
        $itemsText = implode("\n", $items);

        $address = $order->address;
        $addressText = $address ? trim(($address->recipient_name ?? '') . ', ' . ($address->address ?? '')) : 'N/A';

        return "🔔 <b>New Order Placed</b>\n\n" .
               "Order #: <b>{$order->order_number}</b>\n" .
               "Customer: {$order->user->name}\n" .
               "Phone: " . ($address->phone ?? 'N/A') . "\n\n" .
               "<b>Items:</b>\n{$itemsText}\n\n" .
               "Subtotal: $" . number_format($order->subtotal, 2) . "\n" .
               "Discount: $" . number_format($order->discount_amount, 2) . "\n" .
               "Delivery Fee: $" . number_format($order->delivery_fee, 2) . "\n" .
               "<b>Total: $" . number_format($order->total_amount, 2) . "</b>\n\n" .
               "Payment: {$order->payment_method} ({$order->payment_status})\n" .
               "Address: {$addressText}";
    }
}