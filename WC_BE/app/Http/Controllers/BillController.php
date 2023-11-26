<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills = Bill::all();
        // $bills = $this->getFee();
        return response()->json(['bill' => $bills]);
    }
    public function show(Bill $bill)
    {
        return response()->json($bill);
    }
    private function getPaymentUrl()
    {
        $KEY = env('STRIPE_SECRET_API_KEY');
        $stripe = new \Stripe\StripeClient($KEY);
        $payment = $stripe->paymentLinks->all([
            'limit' => 1,
        ]);

        return $payment['data'][0]['url'];
    }

    private function getFee()
    {
        $KEY = env('STRIPE_SECRET_API_KEY');
        $stripe = new \Stripe\StripeClient($KEY);
        $fee = $stripe->prices->all([
            'limit' => 1,
        ]);
        return $fee['data'][0]['unit_amount_decimal'];
    }

    public function store(Request $request)
    {
        $request->validate([
            'payer' => 'required|string',
        ]);

        $request = $request->merge([
            'fee_type' => 'stripe',
            'fee' => $this->getFee(),
            'bill_at' => date('Y-m-d H:i:s'),
            'month' => date('m'),
            'year' => date('Y'),
            'description' => 'Thanh toán qua Stripe',
            'created_by' => 'admin',
        ]);

        $bill = Bill::create($request->all());

        return response()->json(['bill' => $bill, 'message' => 'Bill created successfully']);
    }
    
}
