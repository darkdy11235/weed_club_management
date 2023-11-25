<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('reset-password', [ResetPasswordController::class, 'reset']);

Route::prefix('payment')->group(function () {
    Route::get('all', [PaymentController::class, 'getAllPayments']);
    Route::get('user/{id}', [PaymentController::class, 'getPaymentsByUserId']);
    Route::get('detail/{paymentId}', [PaymentController::class, "getPaymentDetail"]);
});

Route::prefix('bill')->group(function(){
    Route::get("all", [BillController::class, "getAllBill"]);
    Route::get("read/{id}", [BillController::class], "getBillById");
    Route::post('create', [BillController::class, "createBill"]);
    Route::put('update', BillController::class, "updateBill");
    Route::delete("delete/{id}", BillController::class, "deleteBill");
});
 
// Authenticated Routes (requires a valid token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('user', [LoginController::class, 'user']);
});
