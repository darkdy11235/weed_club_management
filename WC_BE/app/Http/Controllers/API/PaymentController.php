<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // Create a new payment
    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            // Add any other validation rules as needed
        ]);

        $payment = Payment::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'status' => $request->status,
            // Add other fields as needed
        ]);

        return response()->json(['message' => 'Payment created successfully', 'data' => $payment], 201);
    }

    // Get all payments
    public function index()
    {
        $payments = Payment::all();

        return response()->json(['data' => $payments]);
    }

    // Get a specific payment by ID
    public function show($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        return response()->json(['data' => $payment]);
    }

    // Update a payment by ID
    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $request->validate([
            'user_id' => 'exists:users,id',
            'amount' => 'numeric',
            'status' => 'string',
            // Add any other validation rules as needed
        ]);

        $payment->update([
            'user_id' => $request->input('user_id', $payment->user_id),
            'amount' => $request->input('amount', $payment->amount),
            'status' => $request->input('status', $payment->status),
            // Update other fields as needed
        ]);

        return response()->json(['message' => 'Payment updated successfully', 'data' => $payment]);
    }

    // Delete a payment by ID
    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }

    public function getPaymentsByUser(Request $request)
    {
        $payments = Payment::where('user_id', $request->user()->id)->get();
        
        return response()->json(['data' => $payments]);
    }
}
