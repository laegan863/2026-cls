<?php

namespace App\Notifications;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RenewalStatusNotification extends Notification
{
    use Queueable;

    protected License $license;
    protected string $renewalStatus;

    public function __construct(License $license, string $renewalStatus)
    {
        $this->license = $license;
        $this->renewalStatus = $renewalStatus;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = $this->renewalStatus === License::RENEWAL_OPEN 
            ? 'Your license is due for renewal. Please renew your license to continue operations.'
            : 'Your license renewal has been processed successfully.';
        
        return (new MailMessage)
            ->subject('License Renewal Notice')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($message)
            ->line('Transaction ID: ' . $this->license->transaction_id)
            ->line('Expiration Date: ' . $this->license->expiration_date->format('M d, Y'))
            ->action('View License', route('admin.licenses.show', $this->license))
            ->line('Thank you for using our services!');
    }

    public function toArray(object $notifiable): array
    {
        $message = $this->renewalStatus === License::RENEWAL_OPEN 
            ? 'License renewal is now open for ' . $this->license->transaction_id
            : 'License renewal completed for ' . $this->license->transaction_id;

        return [
            'type' => 'renewal_status',
            'license_id' => $this->license->id,
            'transaction_id' => $this->license->transaction_id,
            'renewal_status' => $this->renewalStatus,
            'expiration_date' => $this->license->expiration_date->format('M d, Y'),
            'message' => $message,
            'url' => route('admin.licenses.show', $this->license),
        ];
    }
}
