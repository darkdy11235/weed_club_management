<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        \Stripe\Stripe::setApiKey('sk_test_51OGP23LXvx853lUVHlos0Uvvqu050bn6FqWu0RFBNjKiCeY9dyZsRlePSLu02MGZ2XNADgqOOyaOiQhDXZInmmKL00MW5hR6Nw');

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
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                $this->handlePaymentMethodAttached($paymentMethod);
                break;
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
        error_log('PaymentIntent succeeded: ' . $paymentIntent);
    }

    private function handlePaymentMethodAttached($paymentMethod)
    {
        // Your logic to handle an attached payment method
        // For example, log it
        error_log('PaymentMethod attached: ' . $paymentMethod);
    }

}
    