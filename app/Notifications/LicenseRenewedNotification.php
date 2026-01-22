<?php

namespace App\Notifications;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class LicenseRenewedNotification extends Notification
{
    use Queueable;

    protected License $license;
    protected ?string $evidenceFile;
    protected string $newExpirationDate;

    public function __construct(License $license, string $newExpirationDate, ?string $evidenceFile = null)
    {
        $this->license = $license;
        $this->newExpirationDate = $newExpirationDate;
        $this->evidenceFile = $evidenceFile;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('🎉 Your License Has Been Renewed!')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Great news! Your license has been successfully renewed and processed.')
            ->line('')
            ->line('**📋 License Details:**')
            ->line('• **Transaction ID:** ' . $this->license->transaction_id)
            ->line('• **Legal Name:** ' . $this->license->legal_name)
            ->line('• **New Expiration Date:** ' . $this->newExpirationDate);

        if ($this->evidenceFile) {
            $mail->line('')
                 ->line('**📎 Renewal Evidence Document Available**')
                 ->line('A renewal certificate/evidence document has been uploaded for your records. Click the button below to view your license and download your renewal document.')
                 ->action('📥 View License & Download Document', route('admin.licenses.show', $this->license))
                 ->line('')
                 ->line('Please save this document for your records as proof of your license renewal.');
        } else {
            $mail->action('View License Details', route('admin.licenses.show', $this->license));
        }

        return $mail->line('')
                    ->line('Thank you for your continued trust in our services!')
                    ->salutation('Best regards, CLS Team');
    }

    public function toArray(object $notifiable): array
    {
        $message = 'Your license "' . $this->license->legal_name . '" has been renewed!';
        
        if ($this->evidenceFile) {
            $message .= ' A renewal evidence document is available for download.';
        }

        return [
            'type' => 'license_renewed',
            'license_id' => $this->license->id,
            'transaction_id' => $this->license->transaction_id,
            'legal_name' => $this->license->legal_name,
            'new_expiration_date' => $this->newExpirationDate,
            'has_evidence_file' => !empty($this->evidenceFile),
            'evidence_file_url' => $this->evidenceFile ? Storage::url($this->evidenceFile) : null,
            'message' => $message,
            'url' => route('admin.licenses.show', $this->license),
        ];
    }
}
