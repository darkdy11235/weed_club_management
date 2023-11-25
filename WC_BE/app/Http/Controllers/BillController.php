<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function getAllBill(){
        try {
            $result = Bill::getAllBill();
            return response()->json($result, 200);
        } catch (\Exception $error) {
            return response()->json($error, 401);
        }
    }

    public function createBill(Request $request){
        $body = $request->input();

        try {
            $result = Bill::createBill($body);
            return response()->json($result, 200);
        } catch (\Exception $error) {
            return response()->json($error, 401);
        }
    }

    public function updateBill(Request $request){
        $body = $request->input();
        try {
            $result = Bill::updateBill($body);
            return response()->json($result, 200);
        } catch (\Exception $error) {
            return response()->json($error, 401);
        }
    }

    public function getBillById($id) {
        try {
            $result = Bill::getBillById($id);
            return response()->json($result, 200);
        } catch (\Exception $error) {
            return response()->json($error, 401);
        }
    }

    public function deleteBill($id) {
        try {
            $result = Bill::deleteBill($id);
            return response()->json($result, 200);
        } catch (\Exception $error) {
            return response()->json($error, 401);
        }
    }
}
