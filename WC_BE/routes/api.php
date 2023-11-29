<?php

use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserRoleController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\RolePermissionController;
use App\Http\Controllers\API\PermissionController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\ResetPasswordController;

// Public routes

Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/password/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ResetPasswordController::class, 'resetPassword']);

// Protected routes with authentication middleware
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/paidBills', [UserController::class, 'getPaidBills']);
    Route::get('/unPaidBills', [UserController::class, 'getUnPaidBills']);






    Route::post('/payment/create-intent', [PaymentController::class, 'createPaymentIntent']);
    Route::post('/payment/webhook', [PaymentController::class, 'stripeWebhook']);
    // User routes
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user/update', [UserController::class, 'update']);
    Route::post('/user/update', [UserController::class, 'updateAvatar']);
    Route::put('/user/change-password', [UserController::class, 'changePassword']);
    
    // Logout route
    Route::post('/logout', [LoginController::class, 'logout']);
    
    // Routes for managing users
    Route::get('/users', [UserController::class, 'index'])
    ->middleware('checkPermission:read_user');
    Route::get('/users/{id}', [UserController::class, 'showById'])
    ->middleware('checkPermission:read_user');
    Route::post('/users', [UserController::class, 'create'])
    ->middleware('checkPermission:create_user');
    Route::put('/users/{id}', [UserController::class, 'updateById'])
    ->middleware('checkPermission:update_user');
    Route::delete('/users/{id}', [UserController::class, 'deleteById'])
    ->middleware('checkPermission:delete_user');

    // Routes for managing user roles
    Route::middleware('checkPermission:assign_user_roles')->group(function () {
        Route::post('/user/assign-role', [UserRoleController::class, 'assignRole']);
        Route::post('/user/remove-role', [UserRoleController::class, 'removeRole']);
    });

    // Routes for managing role permissions
    Route::middleware('checkPermission:assign_role_permissions')->group(function () {
        Route::get('/user/getAllRolePermission', [RolePermissionController::class, 'getAll']);
        Route::post('/user/assign-permission', [RolePermissionController::class, 'assignPermission']);
        Route::post('/user/remove-permission', [RolePermissionController::class, 'removePermission']);
    });

    // Routes for managing role
    Route::get('/roles', [RoleController::class, 'getAllRoles'])
    ->middleware('checkPermission:read_role');
    Route::post('/role/create', [RoleController::class, 'createRole'])
    ->middleware('checkPermission:create_role');
    Route::put('/role/update', [RoleController::class, 'updateRole'])
    ->middleware('checkPermission:update_role');
    Route::delete('/role/delete', [RoleController::class, 'deleteRole'])
    ->middleware('checkPermission:delete_role');

    // Routes for managing permission
    Route::get('/permissions', [PermissionController::class, 'getAllPermissions'])
    ->middleware('checkPermission:read_permission');
    Route::post('/permission/create', [PermissionController::class, 'createPermission'])
    ->middleware('checkPermission:create_permission');
    Route::put('/permission/update', [PermissionController::class, 'updatePermission'])
    ->middleware('checkPermission:update_permission');
    Route::delete('/permission/delete', [PermissionController::class, 'deletePermission'])
    ->middleware('checkPermission:delete_permission');
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