<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function getAllBill()
    {
        try {
            $result = DB::table('bills')->get();
            return response()->json($result, 200);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 401);
        }
    }

    public function createBill(Request $request)
    {
        try {
            // Validate the request data here if needed

            $newBillId = DB::table('bills')->insertGetId([
                'fee_type' => $request->input('fee_type'),
                'payer' => $request->input('payer'),
                'fee' => $request->input('fee'),
                'bill_at' => $request->input('bill_at'),
                'month' => $request->input('month'),
                'year' => $request->input('year'),
                'description' => $request->input('description'),
                'created_by' => $request->input('created_by'),
                // Add other fields as needed
            ]);

            return response()->json(['bill_id' => $newBillId], 201);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 400);
        }
    }

    public function updateBill(Request $request, $billId)
    {
        try {
            // Validate the request data here if needed

            DB::table('bills')
                ->where('id', $billId)
                ->update([
                    'fee_type' => $request->input('fee_type'),
                    'payer' => $request->input('payer'),
                    'fee' => $request->input('fee'),
                    'bill_at' => $request->input('bill_at'),
                    'month' => $request->input('month'),
                    'year' => $request->input('year'),
                    'description' => $request->input('description'),
                    'created_by' => $request->input('created_by'),
                    // Update other fields as needed
                ]);

            return response()->json(['message' => 'Bill updated successfully'], 200);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 400);
        }
    }

    public function getBillById($billId)
    {
        try {
            $bill = DB::table('bills')
                ->where('id', $billId)
                ->first();

            if (!$bill) {
                return response()->json(['error' => 'Bill not found'], 404);
            }

            return response()->json(['bill' => $bill], 200);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 401);
        }
    }

    public function deleteBill($billId)
    {
        try {
            DB::table('bills')
                ->where('id', $billId)
                ->delete();

            return response()->json(['message' => 'Bill deleted successfully'], 200);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 401);
        }
    }

    public function getPaidBillsByUserId($userId)
    {
        try {
            // Your logic to retrieve paid bills for a specific user
            // ...

            return response()->json(['paid_bills' => $paidBills], 200);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 401);
        }
    }
}
