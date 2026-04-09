<?php

namespace App\Mail;

use App\Models\User;
use App\Models\PlacementProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyJobMatches extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public PlacementProfile $profile,
        public string $selectedRole,
        public array $jobs
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->user->email,
            subject: "Your Weekly Job Matches - {$this->selectedRole}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly-job-matches',
            with: [
                'user' => $this->user,
                'profile' => $this->profile,
                'selectedRole' => $this->selectedRole,
                'jobs' => $this->jobs,
            ],
        );
    }
}
