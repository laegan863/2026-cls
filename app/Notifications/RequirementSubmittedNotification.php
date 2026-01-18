<?php

namespace App\Notifications;

use App\Models\License;
use App\Models\LicenseRequirement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequirementSubmittedNotification extends Notification
{
    use Queueable;

    protected License $license;
    protected LicenseRequirement $requirement;

    public function __construct(License $license, LicenseRequirement $requirement)
    {
        $this->license = $license;
        $this->requirement = $requirement;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Requirement Submitted - ' . $this->license->transaction_id)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A client has submitted a requirement for license application.')
            ->line('**Transaction ID:** ' . $this->license->transaction_id)
            ->line('**Client:** ' . $this->license->client->name)
            ->line('**Requirement:** ' . $this->requirement->label)
            ->action('Review Submission', route('admin.licenses.requirements.index', $this->license))
            ->line('Please review and approve or reject the submitted requirement.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'requirement_submitted',
            'license_id' => $this->license->id,
            'transaction_id' => $this->license->transaction_id,
            'requirement_id' => $this->requirement->id,
            'requirement_label' => $this->requirement->label,
            'client_name' => $this->license->client->name,
            'message' => 'Client "' . $this->license->client->name . '" submitted requirement "' . $this->requirement->label . '" for review.',
            'url' => route('admin.licenses.requirements.index', $this->license),
        ];
    }
}
