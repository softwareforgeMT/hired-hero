<?php

namespace App\Mail;

use App\Models\User;
use App\Models\PromoCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPromoCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $promoCode;
    public $discountedWeeklyPrice;
    public $discountedMonthlyPrice;
    public $unsubscribeToken;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, PromoCode $promoCode, $unsubscribeToken = null)
    {
        $this->user = $user;
        $this->promoCode = $promoCode;
        $this->discountedWeeklyPrice = 4.99 * (1 - $promoCode->discount_percentage / 100);
        $this->discountedMonthlyPrice = 19.00 * (1 - $promoCode->discount_percentage / 100);
        $this->unsubscribeToken = $unsubscribeToken;
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
            view: 'emails.promo-code',
            with: [
                'user' => $this->user,
                'promoCode' => $this->promoCode,
                'discountedWeeklyPrice' => $this->discountedWeeklyPrice,
                'discountedMonthlyPrice' => $this->discountedMonthlyPrice,
                'unsubscribeToken' => $this->unsubscribeToken,
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
