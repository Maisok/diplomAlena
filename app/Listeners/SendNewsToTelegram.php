<?php

namespace App\Listeners;

use App\Events\NewsCreated;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendNewsToTelegram
{
    public function handle(NewsCreated $event)
    {
        $news = $event->news;
        $message = "📢 *Новая новость!* \n\n*{$news->title}*\n\n{$news->description}\n\nЧитать далее: " . url("/news/{$news->id}");

        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHANNEL_ID'),
            'text' => $message,
            'parse_mode' => 'Markdown',
        ]);
    }
}