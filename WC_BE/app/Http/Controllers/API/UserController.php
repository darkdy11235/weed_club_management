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
            $user = $request->user();

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
            $cloudinaryAvatarxxx = "";
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->getRealPath();
                $cloudinaryAvatar = Cloudinary::upload($avatarPath)->getSecurePath();
                $cloudinaryAvatarxxx = $cloudinaryAvatar;
                $user->avatar = $cloudinaryAvatar;
            }

            // Save the changes
            $user->save();

            return response()->json(['message' => 'User updated successfully', 'user' => $cloudinaryAvatarxxx]);
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
            $cloudinaryAvatarxxx = "";
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->getRealPath();
                $cloudinaryAvatar = Cloudinary::upload($avatarPath)->getSecurePath();
                $cloudinaryAvatarxxx = $cloudinaryAvatar;
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
}

