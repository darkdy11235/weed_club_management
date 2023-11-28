<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StripeWebhookController;
Route::get('/', function () {
    return view('welcome');
});

Route::any('/webhook',[StripeWebhookController::class,'handle']);