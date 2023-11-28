<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
  public function getAllPayments() {
      $payments = Payment::getAllPayments();

      return response()->json(['data' => $payments], 200);
  }

  public function getPaymentsByUserId($id) {
      try {
          $payments = Payment::getPaymentsByUserId($id);
          return response()->json(['data' => $payments], 200);
      } catch (\Exception $error) {
          return response()->json(['data' => $error], 401);
      }
  }

  public function getPaymentDetail($paymentId){
      try{
          $paymentDetail = Payment::getPaymentDetail($paymentId);

          return response()->json(['data' => $paymentDetail], 200);
      }catch (\Exception $error) {
          return response()->json(['data' => $error], 401);
      }
  }
}
