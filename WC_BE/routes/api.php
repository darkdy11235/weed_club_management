<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\UserController;


//user
Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('/logout', [UserController::class, 'logout']);