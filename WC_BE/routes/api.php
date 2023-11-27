<?php

use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TestTroller;

// User Public routes
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// User Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // User routes
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user/update', [UserController::class, 'update']);
    Route::post('/user/update', [UserController::class, 'updateAvatar']);
    Route::put('/user/change-password', [UserController::class, 'changePassword']);
    
    // Logout route
    Route::post('/logout', [LoginController::class, 'logout']);
});


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