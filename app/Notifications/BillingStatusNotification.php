<?php

namespace App\Notifications;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BillingStatusNotification extends Notification
{
    use Queueable;

    protected License $license;
    protected string $billingStatus;
    protected ?float $amount;

    public function __construct(License $license, string $billingStatus, ?float $amount = null)
    {
        $this->license = $license;
        $this->billingStatus = $billingStatus;
        $this->amount = $amount;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusMessage = $this->getStatusMessage();
        
        $mail = (new MailMessage)
            ->subject('Billing Status Update: ' . ucfirst($this->billingStatus))
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($statusMessage)
            ->line('Transaction ID: ' . $this->license->transaction_id);
        
        if ($this->amount) {
            $mail->line('Amount: $' . number_format($this->amount, 2));
        }
        
        return $mail
            ->action('View License', route('admin.licenses.show', $this->license))
            ->line('Thank you for using our services!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'billing_status',
            'license_id' => $this->license->id,
            'transaction_id' => $this->license->transaction_id,
            'billing_status' => $this->billingStatus,
            'amount' => $this->amount,
            'message' => $this->getShortMessage(),
            'url' => route('admin.licenses.show', $this->license),
        ];
    }

    protected function getStatusMessage(): string
    {
        return match($this->billingStatus) {
            License::BILLING_PENDING => 'Your billing is pending. An invoice will be generated soon.',
            License::BILLING_OPEN => 'Your billing is now open. Please review the pending charges.',
            License::BILLING_INVOICED => 'An invoice has been generated for your license. Please complete the payment.',
            License::BILLING_PAID => 'Your payment has been received and processed. Thank you!',
            License::BILLING_OVERRIDDEN => 'Your billing has been overridden by an administrator.',
            License::BILLING_CLOSED => 'Your billing has been closed.',
            default => 'Your billing status has been updated to: ' . ucfirst($this->billingStatus),
        };
    }

    protected function getShortMessage(): string
    {
        $message = 'Billing status updated to ' . ucfirst($this->billingStatus);
        if ($this->amount) {
            $message .= ' - $' . number_format($this->amount, 2);
        }
        return $message;
    }
}
