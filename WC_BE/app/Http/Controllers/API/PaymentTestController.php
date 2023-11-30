<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentTestController extends Controller
{
    public function showCheckoutForm(Request $request)
    {
        $billIds = explode('|', $request->bill_ids);
        $amount_bills = DB::table('bills')
            ->whereIn('id', $billIds)
            ->sum('fee');

        return view('checkout', ['amount_bills' => $amount_bills]);
    }

    public function showConfirmationForm()
    {
        return view('confirmation');
    }
}
