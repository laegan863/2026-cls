<?php

namespace App\Notifications;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LicenseStatusNotification extends Notification
{
    use Queueable;

    protected License $license;
    protected string $oldStatus;
    protected string $newStatus;
    protected string $changedBy;

    public function __construct(License $license, string $oldStatus, string $newStatus, string $changedBy = 'System')
    {
        $this->license = $license;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->changedBy = $changedBy;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusMessage = $this->getStatusMessage();
        
        return (new MailMessage)
            ->subject('License Status Update: ' . ucfirst($this->newStatus))
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($statusMessage)
            ->line('Transaction ID: ' . $this->license->transaction_id)
            ->line('Previous Status: ' . ucfirst(str_replace('_', ' ', $this->oldStatus)))
            ->line('Current Status: ' . ucfirst(str_replace('_', ' ', $this->newStatus)))
            ->action('View License', route('admin.licenses.show', $this->license))
            ->line('Thank you for using our services!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'license_status',
            'license_id' => $this->license->id,
            'transaction_id' => $this->license->transaction_id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'changed_by' => $this->changedBy,
            'message' => $this->getShortMessage(),
            'url' => route('admin.licenses.show', $this->license),
        ];
    }

    protected function getStatusMessage(): string
    {
        return match($this->newStatus) {
            License::STATUS_SUBMITTED => 'Your license application has been submitted and is pending review.',
            License::STATUS_UNDER_REVIEW => 'Your license application is now under review by our team.',
            License::STATUS_APPROVED => 'Congratulations! Your license application has been approved.',
            License::STATUS_REJECTED => 'Unfortunately, your license application has been rejected. Please contact support for more details.',
            License::STATUS_ON_HOLD => 'Your license application has been placed on hold. Additional information may be required.',
            License::STATUS_CANCELLED => 'Your license application has been cancelled.',
            default => 'Your license status has been updated to: ' . ucfirst(str_replace('_', ' ', $this->newStatus)),
        };
    }

    protected function getShortMessage(): string
    {
        return 'License status changed from ' . ucfirst(str_replace('_', ' ', $this->oldStatus)) . 
               ' to ' . ucfirst(str_replace('_', ' ', $this->newStatus));
    }
}
