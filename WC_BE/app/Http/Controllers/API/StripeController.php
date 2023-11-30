<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User; // Import User model
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Database\QueryException;
use App\Models\Role;
use App\Models\UserRole;
use Stripe\Stripe;

class StripeController extends Controller
{
    // public function checkout()
    // {
    //     return view('checkout');
    // }
 
    public function session(Request $request)
    {
        $selectedBills = [4, 5, 6];
        $totalAmount = 0;
        foreach ($selectedBills as $selectedBill) {
            $totalAmount += DB::table('bills')
                ->where('id', $selectedBill)
                ->value('fee');
        }

        Stripe::setApiKey('sk_test_51OHViTDQ4uW48PW6LVGXuZtF8hvO8JHQEcKjjB73xoEAvFQCyNQKWp3WHZaNevkkICXfro1piV8LVvug9hTWojP900q50S7dWT');

        $payment = Payment::create([
            'user_id' => 1, // Assuming you're using authentication
            'amount' => $totalAmount,
            'status' => 'unpaid',
        ]);
        $paymentName = "Payment for ";
        foreach ($selectedBills as $selectedBill) {
            $paymentName .= DB::table('bills')
                ->where('id', $selectedBill)
                ->value('description');
        }
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'USD',
                        'product_data' => [
                            "name" => $paymentName,
                        ],
                        'unit_amount'  => $totalAmount * 100,
                    ],
                    'quantity'   => 1,
                ],
            ],
            'mode'        => 'payment',
            'success_url' => route('success', ['paymentId' => $payment->id]),
            'metadata'    => [
                'payment_id' => $payment->id,
            ],
        ]);
 
        return redirect()->away($session->url);
    }
 
    public function success()
    {
        $payment = Payment::findOrFail(36);

        if ($payment->status === 'unpaid') {
            $payment->status = 'paid';
            $payment->save();

            // return json response
            return response()->json(['message' => 'payment successfully']);
        } else {
            // Nếu trạng thái không phải 'unpaid', xử lý theo ý của bạn
            return redirect()->route('payment-failed')->with('error', 'Thanh toán đã được xử lý trước đó');
        }
        
       
    }
}
