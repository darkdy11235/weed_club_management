<?php

use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TestTroller;


//user
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::prefix('payment')->group(function () {
  Route::get('all', [PaymentController::class, 'getAllPayments']);
  Route::get('user/{id}', [PaymentController::class, 'getPaymentsByUserId']);
  Route::get('detail/{paymentId}', [PaymentController::class, "getPaymentDetail"]);
});

Route::prefix('bill')->group(function(){
  Route::get("all", [BillController::class, "getAllBill"]);
  Route::get("read/{id}", [BillController::class, "getBillById"]);
  Route::post('create', [BillController::class, "createBill"]);
  Route::put('update', [BillController::class, "updateBill"]);
  Route::delete("delete/{id}", [BillController::class, "deleteBill"]);
});

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);