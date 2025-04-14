<?php 

namespace App\Services;

use Exception;

class TelegramReport {
    public static function report($message)  {
        try {
            $telegram = (new \Telegram\Bot\Api(env('TELEGRAM_BOT_KEY')))->sendMessage([
                'chat_id' => env('TELEGRAM_CHAT_ID'),
                'text' => $message,
            ]);
        } catch (Exception $e) {
        }
    }
}