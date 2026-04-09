<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Resume;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResumeCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $resume;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Resume $resume)
    {
        $this->user = $user;
        $this->resume = $resume;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your AI-Powered Resume is Ready! 🎉',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.resume-created',
            with: [
                'user' => $this->user,
                'resume' => $this->resume,
                'downloadUrl' => route('resume.download', $this->resume->id),
                'viewUrl' => route('resume-builder.view', $this->resume->id),
            ],
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
