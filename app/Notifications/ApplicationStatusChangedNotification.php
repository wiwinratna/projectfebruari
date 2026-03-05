<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $eventTitle,
        private readonly string $openingTitle,
        private readonly string $status,
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
            'type' => 'application_status_changed',
            'event_title' => $this->eventTitle,
            'opening_title' => $this->openingTitle,
            'status' => $this->status,
            'issued_at' => null,
            'title' => 'Application status updated',
            'message' => "Your application has been {$this->status}.",
            'link' => $this->link,
        ];
    }
}
