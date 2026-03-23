<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $data) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nieuw contactbericht')
            ->greeting('Er is een nieuw contactbericht binnengekomen.')
            ->line('Naam: '.$this->data['name'])
            ->line('E-mail: '.$this->data['email'])
            ->line('Bericht:')
            ->line($this->data['message']);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
