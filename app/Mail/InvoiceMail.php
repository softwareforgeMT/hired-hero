<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;



    public $user;
    public $order;
    public $plan;

    public function __construct($user, $order, $plan)
    {
        $this->user = $user;
        $this->order = $order;
        $this->plan = $plan;
    }

    public function build()
    {
        return $this->view('emails.invoice')
                    ->subject('Your Subscription Invoice')
                    ->with([
                        'user' => $this->user,
                        'order' => $this->order,
                        'plan' => $this->plan,
                    ]);
    }
    
}
