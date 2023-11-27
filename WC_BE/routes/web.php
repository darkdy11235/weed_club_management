<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeWebhookController;
Route::get('/', function () {
    return view('welcome');
});

Route::any('/webhook',[StripeWebhookController::class,'handle']);