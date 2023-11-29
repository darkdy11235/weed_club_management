<?php

// app/Http/Controllers/API/PaymentController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'pay_account' => 'required',
            'pay_method' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            // Create a Payment record in the database
            $payment = Payment::create([
                'user_id' => auth()->id(), // Assuming you're using authentication
                'pay_account' => $request->pay_account,
                'pay_method' => $request->pay_method,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'amount_money' => $request->amount,
                'description' => $request->description,
            ]);

            // Create a PaymentIntent using the payment ID
            $intent = PaymentIntent::create([
                'amount' => $request->amount * 100, // Stripe uses cents
                'currency' => 'usd',
                'metadata' => ['payment_id' => $payment->id],
            ]);

            return response()->json(['client_secret' => $intent->client_secret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllPayments()
    {
        $payments = Payment::all();

        return response()->json(['payments' => $payments]);
    }

    public function getPaymentsByUserId($userId)
    {
        $payments = Payment::where('user_id', $userId)->get();

        return response()->json(['payments' => $payments]);
    }

    public function getPaymentDetail($paymentId)
    {
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        return response()->json(['payment' => $payment]);
    }

    // Add other methods as needed
}
