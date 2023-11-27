<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string|in:Female,Male',
            'phone' => 'required|string|unique:users',
            'address' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Name is required',
            'age.required' => 'Age is required',
            'gender.required' => 'Gender is required',
            'phone.required' => 'Phone is required',
            'phone.unique' => 'The phone number is already in use. Please choose a different one.',
            'address.required' => 'Address is required',
            'email.required' => 'Email is required',
            'email.unique' => 'The email address is already in use. Please choose another email address.',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Password does not match',
        ]);

        if ($validator->fails()) {
            // Return validation errors as JSON
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $user = User::create([
                'name' => $request->input('name'),
                'age' => $request->input('age'),
                'gender' => $request->input('gender'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['token' => $token])->setName('api.token');
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
}
