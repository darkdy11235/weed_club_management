<?php

use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\PaymentTestController;
use App\Http\Controllers\API\RegistrationController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\UserRoleController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\StripeController;
use App\Http\Controllers\API\RolePermissionController;
use App\Http\Controllers\API\PermissionController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\VerificationController;
use App\Http\Controllers\API\ResetPasswordController;
use Illuminate\Support\Facades\Route;
// Public routes

Route::post('/register', [RegistrationController::class, 'register']);
Route::post('/verify-user', [VerificationController::class, 'verifyUserByCode']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password-reset', [ResetPasswordController::class, 'reset'])->name('password-reset');

// Protected routes with authentication middleware
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/bills', [BillController::class, 'getAllBill'])
    ->middleware('checkPermission:read_bill');

    // Create a new bill
    Route::post('/bills', [BillController::class, 'createBill'])
    ->middleware('checkPermission:create_bill');
    // Update an existing bill
    Route::put('/bills/{billId}', [BillController::class, 'updateBill'])
    ->middleware('checkPermission:update_bill');
    // Retrieve a specific bill by ID
    Route::get('/bills/{billId}', [BillController::class, 'getBillById'])
    ->middleware('checkPermission:read_bill');
    // Delete a specific bill by ID
    Route::delete('/bills/{billId}', [BillController::class, 'deleteBill'])
    ->middleware('checkPermission:delete_bill');
    // Retrieve paid bills for a specific user
    Route::get('/users/{userId}/paid-bills', [BillController::class, 'getPaidBillsByUserId']);

    Route::get('/paidBills', [UserController::class, 'getPaidBills']);
    Route::get('/unPaidBills', [UserController::class, 'getUnPaidBills']);
    
    Route::get('/session', [StripeController::class, 'session'])->name('session');
    Route::get('/success', [StripeController::class, 'success'])->name('success');
    
    // User routes
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user/update', [UserController::class, 'update']);
    Route::post('/user/update', [UserController::class, 'updateAvatar']);
    Route::put('/user/changePassword', [UserController::class, 'changePassword']);
    
    // Logout route
    Route::post('/logout', [LoginController::class, 'logout']);
    // get bills by year
    Route::get('/get-bills/{year}', [UserController::class, 'getBillsByYear'])
    ->middleware('checkPermission:read_bill');
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
    Route::get('/search/{name}', [UserController::class, 'search']);
    // routes/web.php

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
  