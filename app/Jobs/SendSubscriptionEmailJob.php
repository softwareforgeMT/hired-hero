<?php

namespace App\Jobs;


use App\CentralLogics\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;
class SendSubscriptionEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;
    protected $order;
    protected $plan;

    public function __construct($user,$order,$plan)
    {
        $this->user = $user;
        $this->order = $order;
        $this->plan = $plan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->user->email)->send(new InvoiceMail( $this->user,$this->order, $this->plan));

        Helpers::subscriptionEmailToAdmin($this->user,$this->plan);
    }
}
