<?php

namespace App\Notifications;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequirementAddedNotification extends Notification
{
    use Queueable;

    protected License $license;

    public function __construct(License $license)
    {
        $this->license = $license;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Action Required: Missing Requirements for License Application')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your license application (Transaction ID: ' . $this->license->transaction_id . ') requires additional information.')
            ->line('Our team has identified some missing requirements that need to be completed.')
            ->action('View Requirements', route('admin.licenses.requirements.index', $this->license))
            ->line('Please submit the required information as soon as possible to continue with your application.')
            ->line('Thank you for your cooperation!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'requirement_added',
            'license_id' => $this->license->id,
            'transaction_id' => $this->license->transaction_id,
            'message' => 'New requirements have been added to your license application.',
            'url' => route('admin.licenses.requirements.index', $this->license),
        ];
    }
}
