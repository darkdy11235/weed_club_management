<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User; // Import User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Illuminate\Support\Facades\DB;


class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
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
            $payment = Payment::create([
                'user_id' => auth()->id(),
                'pay_account' => $request->pay_account,
                'pay_method' => $request->pay_method,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'amount_money' => $request->amount,
                'description' => $request->description,
            ]);

            $intent = PaymentIntent::create([
                'amount' => $request->amount * 100,
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
    public function showCheckoutForm(Request $request)
    {
        $billIds = explode('|', $request->bill_ids);
        $amount_bills = DB::table('bills')
            ->whereIn('id', $billIds)
            ->sum('fee');

        return view('checkout', ['amount_bills' => $amount_bills]);
    }

    public function checkOut(Request $request)
    {
        $billIds = explode('|', $request->bill_ids);
        $amount_bills = DB::table('bills')
            ->whereIn('id', $billIds)
            ->sum('fee');
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        
        
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            // Create a PaymentIntent using the amount
            $intent = PaymentIntent::create([
                'amount' => $amount_bills * 100, // Stripe uses cents
                'currency' => 'usd',
                'description' => $request->description,
            ]);

            return view('confirm-payment', ['clientSecret' => $intent->client_secret]);
        } catch (ApiErrorException $e) {
            Log::error('Error in checkOut method: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }

    public function showConfirmationForm()
    {
        return view('confirm-payment');
    }

    public function confirmPayment(Request $request)
    {
        // Handle payment confirmation logic
        // This could involve updating your database, sending email receipts, etc.

        return redirect()->route('thank-you')->with('success', 'Payment confirmed successfully!');
    }
    // public function checkOut(Request $request)
    // {
    //     $billIds = explode('|', $request->bill_ids);
    //     $amount_bills = DB::table('bills')
    //         ->whereIn('id', $billIds)
    //         ->sum('fee');

    //     return $amount_bills;
    // }

    // public function confirmPayment(Request $request)
    // {
    //     $userId = $request->user()->id;

    //     $validator = Validator::make($request->all(), [
    //         'client_secret' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $intent = PaymentIntent::retrieve($request->client_secret);
    //     $payment = Payment::find($intent->metadata->payment_id);
    //     $user = User::find($payment->user_id);

    //     $amount = $intent->amount / 100;
    //     $billIds = explode('|', $request->bill_ids);

    //     $amount_bills = DB::table('bills')
    //         ->whereIn('id', $billIds)
    //         ->sum('fee');

    //     if ($amount_bills < $amount) {
    //         return response()->json(['error' => 'Insufficient amount'], 400);
    //     }

    //     $payment->status = 'paid';
    //     $payment->save();

    //     $user->balance += $amount;
    //     $user->save();

    //     return response()->json(['message' => 'Payment confirmed'], 200);
    // }
}
