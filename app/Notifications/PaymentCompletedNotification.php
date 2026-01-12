<?php

namespace App\Notifications;

use App\Models\License;
use App\Models\LicensePayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentCompletedNotification extends Notification
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
        $paymentMethod = $this->payment->payment_method === 'online' ? 'online payment' : 'offline payment';
        
        return (new MailMessage)
            ->subject('Payment Received: License Application')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We have received your payment for your license application.')
            ->line('Transaction ID: ' . $this->license->transaction_id)
            ->line('Invoice Number: ' . $this->payment->invoice_number)
            ->line('Amount Paid: $' . number_format($this->payment->total_amount, 2))
            ->line('Payment Method: ' . ucfirst($paymentMethod))
            ->action('View License', route('admin.licenses.show', $this->license))
            ->line('Thank you for your payment!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_completed',
            'license_id' => $this->license->id,
            'payment_id' => $this->payment->id,
            'transaction_id' => $this->license->transaction_id,
            'invoice_number' => $this->payment->invoice_number,
            'amount' => $this->payment->total_amount,
            'payment_method' => $this->payment->payment_method,
            'message' => 'Payment of $' . number_format($this->payment->total_amount, 2) . ' received.',
            'url' => route('admin.licenses.show', $this->license),
        ];
    }
}
