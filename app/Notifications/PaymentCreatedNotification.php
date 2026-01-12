<?php

namespace App\Notifications;

use App\Models\License;
use App\Models\LicensePayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentCreatedNotification extends Notification
{
    use Queueable;

    protected License $license;
    protected LicensePayment $payment;

    public function __construct(License $license, LicensePayment $payment)
    {
        $this->license = $license;
        $this->payment = $payment;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Required: License Application')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A payment of $' . number_format($this->payment->total_amount, 2) . ' is required for your license application.')
            ->line('Transaction ID: ' . $this->license->transaction_id)
            ->line('Invoice Number: ' . $this->payment->invoice_number)
            ->action('Pay Now', route('admin.licenses.payments.show', $this->license))
            ->line('You can pay online using your credit/debit card or contact us for offline payment options.')
            ->line('Thank you!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_created',
            'license_id' => $this->license->id,
            'payment_id' => $this->payment->id,
            'transaction_id' => $this->license->transaction_id,
            'invoice_number' => $this->payment->invoice_number,
            'amount' => $this->payment->total_amount,
            'message' => 'A payment of $' . number_format($this->payment->total_amount, 2) . ' is required.',
            'url' => route('admin.licenses.payments.show', $this->license),
        ];
    }
}
