<?php

namespace App\Mail;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class LicenseRenewedMail extends Mailable
{
    use Queueable, SerializesModels;

    public License $license;
    public string $newExpirationDate;
    public ?string $evidenceFile;
    public ?string $evidenceFileUrl;
    public string $licenseUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(License $license, string $newExpirationDate, ?string $evidenceFile = null)
    {
        $this->license = $license;
        $this->newExpirationDate = $newExpirationDate;
        $this->evidenceFile = $evidenceFile;
        $this->evidenceFileUrl = $evidenceFile ? Storage::url($evidenceFile) : null;
        $this->licenseUrl = route('admin.licenses.show', $license);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Your License Has Been Renewed! - Renewal Document Available',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.license-renewed',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
