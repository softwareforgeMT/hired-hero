<?php

namespace App\Http\Controllers\Placement;

use App\Http\Controllers\Controller;
use App\Services\Placement\StripePaymentService;
use Illuminate\Http\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class StripeWebhookResumeController extends Controller
{
    protected $stripeService;

    public function __construct(StripePaymentService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Handle incoming Stripe webhooks
     */
    public function handle(Request $request)
    {
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                config('services.stripe.webhook_secret')
            );
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);
            return response('Webhook signature verification failed.', 400);
        }

        // Handle the event
        match($event->type) {
            'checkout.session.completed' => $this->handleCheckoutSessionCompleted($event),
            'charge.failed' => $this->handleChargeFailed($event),
            'charge.refunded' => $this->handleChargeRefunded($event),
            'charge.dispute.created' => $this->handleDisputeCreated($event),
            default => null,
        };

        return response('Webhook received', 200);
    }

    /**
     * Handle checkout.session.completed event
     */
    private function handleCheckoutSessionCompleted($event)
    {
        $session = $event->data->object;

        Log::info('Stripe checkout session completed', [
            'session_id' => $session->id,
            'customer_id' => $session->customer,
        ]);

        // Verify session ID is valid
        if (!$session->id || strpos($session->id, '{') === 0) {
            Log::error('Invalid session ID in webhook', [
                'session_id' => $session->id,
            ]);
            return;
        }

        // Verify payment status
        if ($session->payment_status !== 'paid') {
            Log::warning('Checkout session not fully paid', [
                'session_id' => $session->id,
                'payment_status' => $session->payment_status,
            ]);
            return;
        }

        // Extract metadata
        $metadata = $session->metadata ? (array) $session->metadata : [];

        // Validate metadata contains required user_id
        if (empty($metadata['user_id'])) {
            Log::error('Missing user_id in webhook metadata', [
                'session_id' => $session->id,
                'metadata' => $metadata,
            ]);
            return;
        }

        // Create subscription record
        $success = $this->stripeService->handleSuccessfulPayment($session->id, $metadata);

        if ($success) {
            Log::info('Subscription created from webhook', [
                'session_id' => $session->id,
                'user_id' => $metadata['user_id'] ?? null,
            ]);
        } else {
            Log::error('Failed to create subscription from webhook', [
                'session_id' => $session->id,
                'metadata' => $metadata,
            ]);
        }
    }

    /**
     * Handle charge.failed event
     */
    private function handleChargeFailed($event)
    {
        $charge = $event->data->object;

        Log::warning('Stripe charge failed', [
            'charge_id' => $charge->id,
            'amount' => $charge->amount / 100,
            'currency' => $charge->currency,
            'failure_message' => $charge->failure_message,
        ]);

        // Optional: Send email notification to customer
        // Notify::sendChargeFailedEmail($charge->customer);
    }

    /**
     * Handle charge.refunded event
     */
    private function handleChargeRefunded($event)
    {
        $charge = $event->data->object;

        Log::warning('Stripe charge refunded', [
            'charge_id' => $charge->id,
            'amount_refunded' => $charge->amount_refunded / 100,
            'customer_id' => $charge->customer,
        ]);

        // Optional: Cancel or expire the subscription if refunded
        // $this->stripeService->handleRefund($charge->customer);
    }

    /**
     * Handle charge.dispute.created event
     */
    private function handleDisputeCreated($event)
    {
        $dispute = $event->data->object;

        Log::warning('Stripe dispute created', [
            'dispute_id' => $dispute->id,
            'charge_id' => $dispute->charge,
            'amount' => $dispute->amount / 100,
            'reason' => $dispute->reason,
        ]);

        // Optional: Take action on dispute
        // Notify admin or suspend account
    }
}
