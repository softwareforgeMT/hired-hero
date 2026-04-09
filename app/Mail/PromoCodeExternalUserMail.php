<?php

namespace App\Mail;

use App\Models\PromoCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromoCodeExternalUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $promoCode;
    public $discountedWeeklyPrice;
    public $discountedMonthlyPrice;
    public $recipientEmail;

    /**
     * Create a new message instance for external/custom email users.
     */
    public function __construct(PromoCode $promoCode, $recipientEmail = null)
    {
        $this->promoCode = $promoCode;
        $this->recipientEmail = $recipientEmail;
        $this->discountedWeeklyPrice = 4.99 * (1 - $promoCode->discount_percentage / 100);
        $this->discountedMonthlyPrice = 19.00 * (1 - $promoCode->discount_percentage / 100);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Exclusive Promo Code - ' . $this->promoCode->discount_percentage . '% Off Resume Builder!'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.promo-code-external',
            with: [
                'promoCode' => $this->promoCode,
                'discountedWeeklyPrice' => $this->discountedWeeklyPrice,
                'discountedMonthlyPrice' => $this->discountedMonthlyPrice,
                'recipientEmail' => $this->recipientEmail,
            ]
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
