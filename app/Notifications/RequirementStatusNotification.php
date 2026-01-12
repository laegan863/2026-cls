<?php

namespace App\Notifications;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequirementStatusNotification extends Notification
{
    use Queueable;

    protected License $license;
    protected string $status;
    protected ?string $reason;

    public function __construct(License $license, string $status, ?string $reason = null)
    {
        $this->license = $license;
        $this->status = $status;
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->greeting('Hello ' . $notifiable->name . ',');

        if ($this->status === 'approved') {
            $mail->subject('License Application Approved!')
                ->line('Great news! Your license application (Transaction ID: ' . $this->license->transaction_id . ') has been approved.')
                ->line('You can now proceed with the next steps.');
            
            if ($this->license->isPaymentPending()) {
                $mail->line('A payment is required to complete your license. Please check your dashboard for payment details.')
                    ->action('Make Payment', route('admin.licenses.payments.show', $this->license));
            } else {
                $mail->action('View License', route('admin.licenses.show', $this->license));
            }
        } else {
            $mail->subject('License Application: Action Required')
                ->line('Your license application (Transaction ID: ' . $this->license->transaction_id . ') requires your attention.')
                ->line('Reason: ' . $this->reason)
                ->action('View Details', route('admin.licenses.requirements.index', $this->license))
                ->line('Please address the issues and resubmit.');
        }

        return $mail->line('Thank you!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'requirement_status',
            'license_id' => $this->license->id,
            'transaction_id' => $this->license->transaction_id,
            'status' => $this->status,
            'reason' => $this->reason,
            'message' => $this->status === 'approved' 
                ? 'Your license application has been approved!' 
                : 'Your license application requires attention.',
            'url' => $this->status === 'approved' && $this->license->isPaymentPending()
                ? route('admin.licenses.payments.show', $this->license)
                : route('admin.licenses.requirements.index', $this->license),
        ];
    }
}
