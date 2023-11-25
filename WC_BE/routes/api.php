<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('reset-password', [ResetPasswordController::class, 'reset']);

// Authenticated Routes (requires a valid token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('user', [LoginController::class, 'user']);
});

