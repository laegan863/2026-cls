<?php

namespace App\Notifications;

use App\Models\License;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LicenseCreatedNotification extends Notification
{
    use Queueable;

    protected License $license;
    protected ?User $createdBy;

    public function __construct(License $license, ?User $createdBy = null)
    {
        $this->license = $license;
        $this->createdBy = $createdBy;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New License Application Submitted')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new license application has been submitted.')
            ->line('Transaction ID: ' . $this->license->transaction_id)
            ->line('License Type: ' . ($this->license->permit_type ?? 'N/A'))
            ->line('Submitted by: ' . ($this->createdBy ? $this->createdBy->name : 'Client'))
            ->action('View License', route('admin.licenses.show', $this->license))
            ->line('Please review the application at your earliest convenience.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'license_created',
            'license_id' => $this->license->id,
            'transaction_id' => $this->license->transaction_id,
            'permit_type' => $this->license->permit_type ?? 'N/A',
            'created_by' => $this->createdBy ? $this->createdBy->name : 'Client',
            'message' => 'New license application submitted: ' . $this->license->transaction_id,
            'url' => route('admin.licenses.show', $this->license),
        ];
    }
}
