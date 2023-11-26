<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RegistrationController;

Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', 'API\UserController@getUser');
Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'logout']);
