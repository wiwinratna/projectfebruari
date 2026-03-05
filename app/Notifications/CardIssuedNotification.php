<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CardIssuedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $eventTitle,
        private readonly string $openingTitle,
        private readonly string $issuedAt,
        private readonly string $link
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'card_issued',
            'event_title' => $this->eventTitle,
            'opening_title' => $this->openingTitle,
            'status' => 'issued',
            'issued_at' => $this->issuedAt,
            'title' => 'Access card issued',
            'message' => 'Your access card has been issued.',
            'link' => $this->link,
        ];
    }
}
