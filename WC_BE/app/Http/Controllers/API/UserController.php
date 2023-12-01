<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $roles = $user->getAllRoles();
        $userData = $user->toArray();
        $userData['roles'] = $roles;
        return response()->json(['user' => $userData]);
    }

    public function update(Request $request)
    {
        try {
            $user = $request->user();

            // Compare user data with data from the request
            $updatedFields = [];
            foreach ($request->all() as $key => $value) {
                // Skip fields that shouldn't be updated
                if ($key == 'password' || $key == 'avatar') {
                    if ($key =='avatar') {
                        $avatarPath = $request->file('avatar')->getRealPath();
                        $cloudinaryAvatar = Cloudinary::upload($avatarPath)->getSecurePath();
                        $updatedFields['avatar'] = $cloudinaryAvatar;
                    }
                    continue;
                }
                
                // Compare the field value with the existing user data
                if ($user->{$key} != $value) {
                    $updatedFields[$key] = $value;
                }
            }

            if (!empty($updatedFields)) {
                // Update the user model with the new data
                $user->update($updatedFields);

                return response()->json($updatedFields);
            } else {
                return response()->json(['message' => 'No changes to update.'], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found.'], 404);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // MySQL error code for unique constraint violation
                return response()->json(['error' => 'Duplicate entry. The provided data violates a unique constraint.'], 400);
            }

            // Handle other query exceptions or rethrow for unhandled cases
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateAvatar(Request $request)
    {   
        // Validation rules
        $validator = Validator::make($request->all(), [
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:204800',
        ], [
            'avatar.mimes' => 'The avatar must be a file of type: jpeg, png, jpg, gif, svg.',
            'avatar.max' => 'The avatar may not be greater than 204800 kilobytes.',
        ]);

        // Check for validation errors
        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        try {
            $user = $request->user();

            // Handle avatar separately if it is in the request
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->getRealPath();
                $cloudinaryAvatar = Cloudinary::upload($avatarPath)->getSecurePath();
                $user->avatar = $cloudinaryAvatar;
            }

            // Save the changes
            $user->save();

            return response()->json(['message' => 'User updated successfully', 'user' => $cloudinaryAvatarxxx]);
        } catch (QueryException $e) {
            // Handle other query exceptions or rethrow for unhandled cases
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function changePassword(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Current password is required',
            'new_password.required' => 'New password is required',
            'new_password.min' => 'New password must be at least 6 characters',
            'new_password.confirmed' => 'New password does not match confirmation',
        ]);

        // Check for validation errors
        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        try {
            $user = $request->user();

            // Check if the current password is correct
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = Hash::make($request->input('new_password'));
                $user->save();

                return response()->json(['message' => 'Password changed successfully']);
            } else {
                throw ValidationException::withMessages(['current_password' => 'Invalid current password']);
            }
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // MySQL error code for unique constraint violation
                return response()->json(['error' => 'Duplicate entry. The provided data violates a unique constraint.'], 400);
            }

            // Handle other query exceptions or rethrow for unhandled cases
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users]);
    }

    public function showById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['user' => $user]);
    }

    public function create(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string|in:Female,Male',
            'phone' => 'required|string|unique:users',
            'address' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:204800',
        ]);

        // Check for validation errors
        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        try {
            // Create a new user
            $user = User::create([
                'name' => $request->input('name'),
                'age' => $request->input('age'),
                'gender' => $request->input('gender'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            // Handle avatar separately if it is in the request
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->getRealPath();
                $cloudinaryAvatar = Cloudinary::upload($avatarPath)->getSecurePath();
                $user->avatar = $cloudinaryAvatar;
                $user->save();
            }

            return response()->json(['message' => 'User created successfully', 'user' => $user]);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // MySQL error code for unique constraint violation
                return response()->json(['error query' => 'Duplicate entry. The provided data violates a unique constraint.'], 400);
            }

            // Handle other query exceptions or rethrow for unhandled cases
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateById(Request $request, $id)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'age' => 'integer',
            'gender' => 'string|in:Female,Male',
            'phone' => 'string|unique:users',
            'address' => 'string',
            'email' => 'email|unique:users,email',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:204800',
        ], [
            'phone.unique' => 'The phone number is already in use. Please choose a different one.',
            'email.unique' => 'The email address is already in use. Please choose another email address.',
            'avatar.image' => 'The avatar must be an image.',
            'avatar.mimes' => 'The avatar must be a file of type: jpeg, png, jpg, gif, svg.',
            'avatar.max' => 'The avatar may not be greater than 204800 kilobytes.',
        ]);

        // Check for validation errors
        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        try {
            // Find the user by ID
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Loop through the request data and update corresponding fields
            foreach ($request->all() as $key => $value) {
                // Skip fields that shouldn't be updated
                if ($key == 'password' || $key == 'avatar') {
                    continue;
                }

                // Update the user model
                $user->{$key} = $value;
            }

            // Handle avatar separately if it is in the request
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->getRealPath();
                $cloudinaryAvatar = Cloudinary::upload($avatarPath)->getSecurePath();
                $user->avatar = $cloudinaryAvatar;
            }

            // Save the changes
            $user->save();

            return response()->json(['message' => 'User updated successfully', 'user' => $user]);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // MySQL error code for unique constraint violation
                return response()->json(['error query' => 'Duplicate entry. The provided data violates a unique constraint.'], 400);
            }

            // Handle other query exceptions or rethrow for unhandled cases
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteById($id)
    {
        try {
            // Find the user by ID
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Delete the user
            $user->delete();

            return response()->json(['message' => 'User deleted successfully']);
        } catch (QueryException $e) {
            // Handle other query exceptions or rethrow for unhandled cases
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPaidBills(Request $request)
    {
        $userId = $request->user()->id;
        $paidBills = DB::table('bills')
            ->join('bill_payments', 'bills.id', '=', 'bill_payments.bill_id')
            ->join('payments', 'bill_payments.payment_id', '=', 'payments.id')
            ->where('payments.user_id', $userId)
            ->select('bills.*')
            ->get();

        return response()->json(['paid_bills' => $paidBills]);
    }

    public function getUnPaidBills(Request $request)
    {

        $userId = $request->user()->id;
        // get month created of user
        $dateCreated = DB::table('users')->
            where('id', $userId)->
            select('created_at')->
            first();
        if ($dateCreated) {
            $month = \Carbon\Carbon::parse($dateCreated->created_at)->month;
            $year = \Carbon\Carbon::parse($dateCreated->created_at)->year;

            $paidBills = DB::table('bills')
            ->join('bill_payments', 'bills.id', '=', 'bill_payments.bill_id')
            ->join('payments', 'bill_payments.payment_id', '=', 'payments.id')
            ->where('payments.user_id', $userId)
            ->where('payments.status', "paid")
            ->select('bills.id')
            ->get()
            ->pluck('id');

            $paidBills = $paidBills->toArray();

            $allBills = DB::table('bills')
            ->select('bills.id')
            ->get()
            ->pluck('id');

            $allBills = $allBills->toArray();

            // Calculate unpaid bills by subtracting paid bills from all bills
            $unpaidBillIds = [];

            foreach ($allBills as $bilId) {
        
                if (!in_array($bilId, $paidBills)) {
                    array_push($unpaidBillIds, $bilId);
                }
            }

            // Fetch the details of unpaid bills
            $unpaidBillDetails = DB::table('bills')
            ->whereIn('id', $unpaidBillIds)
            ->where(function ($query) use ($year, $month) {
                $query->where('year', '>', $year)
                    ->orWhere(function ($query) use ($year, $month) {
                        $query->where('year', '=', $year)
                            ->where('month', '>=', $month);
                    });
            })
            ->get();


            return response()->json($unpaidBillDetails);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function getUnPaidBillsByUserId($userId)
    {        
        // get month created of user
        $dateCreated = DB::table('users')->
            where('id', $userId)->
            select('created_at')->
            first();
        if ($dateCreated) {
            $month = \Carbon\Carbon::parse($dateCreated->created_at)->month;
            $year = \Carbon\Carbon::parse($dateCreated->created_at)->year;

            $paidBills = DB::table('bills')
            ->join('bill_payments', 'bills.id', '=', 'bill_payments.bill_id')
            ->join('payments', 'bill_payments.payment_id', '=', 'payments.id')
            ->where('payments.user_id', $userId)
            ->where('payments.status', "paid")
            ->select('bills.id')
            ->get()
            ->pluck('id');

            $paidBills = $paidBills->toArray();

            $allBills = DB::table('bills')
            ->select('bills.id')
            ->get()
            ->pluck('id');

            $allBills = $allBills->toArray();

            // Calculate unpaid bills by subtracting paid bills from all bills
            $unpaidBillIds = [];

            foreach ($allBills as $bilId) {
        
                if (!in_array($bilId, $paidBills)) {
                    array_push($unpaidBillIds, $bilId);
                }
            }

            // Fetch the details of unpaid bills
            $unpaidBillDetails = DB::table('bills')
            ->whereIn('id', $unpaidBillIds)
            ->where(function ($query) use ($year, $month) {
                $query->where('year', '>', $year)
                    ->orWhere(function ($query) use ($year, $month) {
                        $query->where('year', '=', $year)
                            ->where('month', '>=', $month);
                    });
            })
            ->get();


            return response()->json($unpaidBillDetails);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function getBillsByYear($year)
    {
        
        try {
            $listBill = DB::table('bills')
                ->where('bills.year', $year)
                ->select(
                    'bills.month as month',
                    'bills.id as bill_id',
                )
                ->get();

            $listUser = DB::table('users')
                ->select(
                    'users.id as id_user',
                    'users.name',
                )
                ->orderBy('users.id')
                ->get();
                $listFinal = [];

                foreach ($listUser as $user) {
                    $currentUser = [
                        'id_user' => $user->id_user,
                        'name' => $user->name,
                        'bills' => [],
                    ];
                    $userId = $user->id_user;
                    // get month created of user
                    $dateCreated = DB::table('users')->
                    where('id', $userId)->
                    select('created_at')->
                    first();
                    $month = \Carbon\Carbon::parse($dateCreated->created_at)->month;
                    $year = \Carbon\Carbon::parse($dateCreated->created_at)->year;

                    $paidBills = DB::table('bills')
                    ->join('bill_payments', 'bills.id', '=', 'bill_payments.bill_id')
                    ->join('payments', 'bill_payments.payment_id', '=', 'payments.id')
                    ->where('payments.user_id', $userId)
                    ->where('payments.status', "paid")
                    ->select('bills.id')
                    ->get()
                    ->pluck('id');

                    $paidBills = $paidBills->toArray();

                    $allBills = DB::table('bills')
                    ->select('bills.id')
                    ->get()
                    ->pluck('id');

                    $allBills = $allBills->toArray();

                    // Calculate unpaid bills by subtracting paid bills from all bills
                    $unpaidBillIds = [];

                    foreach ($allBills as $bilId) {
                
                        if (!in_array($bilId, $paidBills)) {
                            array_push($unpaidBillIds, $bilId);
                        }
                    }
                    foreach ($listBill as $bill) {
                        $billStatus = in_array($bill->bill_id, $unpaidBillIds)
                            ? 'unpaid'
                            : 'paid';
                
                        $currentUser['bills'][] = [
                            'month' => $bill->month,
                            'bill_id' => $bill->bill_id,
                            'bill_status' => $billStatus,
                        ];
                    }
                
                    $listFinal[] = $currentUser;
                }
            return response()->json($listFinal);
        } catch (\Exception $error) {
            return response()->json(['error' => 'Query error: ' . $error->getMessage()], 500);
        }
    }

    public function search($name)
    {
        $users = User::where('name', 'like', '%' . $name . '%')->get();

        return response()->json(['user' => $users]);
    }
}