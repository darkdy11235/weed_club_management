<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Web routes for user management
Route::group(['middleware' => ['web']], function () {
    // Example: Display a list of users
    Route::get('/users', [UserController::class, 'index']);

    // Example: Display the form to create a new user
    Route::get('/users/create', [UserController::class, 'create']);

    // Example: Store a new user
    Route::post('/users', [UserController::class, 'store']);

    // Other user-related routes can be added here...
});

// Additional web routes can be added outside the group
Route::get('/', function () {
    return view('welcome');
});

// Example: Route with a named middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Example: Route with individual middleware
Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');
