<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//payment controller
use App\Http\Controllers\API\PaymentController;
use App\Models\Bill;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
                // when payment is successful
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                $this->handlePaymentMethodAttached($paymentMethod);
                break;
            case 'payment_intent.canceled':
                $paymentIntent = $event->data->object;
                break;
            case 'payment_link.created':
                $paymentLink = $event->data->object;
                $this->handlePaymentLinkCreated($paymentLink);
                break;
            case 'payment_link.updated':
                $paymentLink = $event->data->object;
                $this->handlePaymentLinkUpdated($paymentLink);
                // ... handle other event types
            default:
                error_log('Received unknown event type ' . $event->type);
        }

        http_response_code(200);
    }
    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Your logic to handle a successful payment intent
        // For example, log it
        $amount = $paymentIntent->amount_received;
        $currency = $paymentIntent->currency;
        $paymentMethod = $paymentIntent->payment_method;
        error_log('PaymentIntent succeeded: ' . $paymentIntent);
        echo ('Amount received: ' . $amount . ' ' . $currency . ' from ' . $paymentMethod);
    }

    private function handlePaymentMethodAttached($paymentMethod)
    {
        // Your logic to handle an attached payment method
        // For example, log it
        error_log('PaymentMethod attached: ' . $paymentMethod);
    }
    
    private function handlePaymentLinkCreated($paymentLink)
    {
        $billId = $this->extractBillId($paymentLink);
        if ($billId) {
            // Update the 'payment_link' column in the 'bills' table
            Bill::where('id', $billId)->update(['payment_link' => $paymentLink]);

            // Log the update (optional)
            error_log("Payment link updated for Bill ID: $billId");
        }
    }

    private function handlePaymentLinkUpdated($paymentLink)
    {
        
    
        error_log('PaymentLink updated: ' . $paymentLink);
    }
}
