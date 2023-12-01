<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function resetUserPasswordByCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'reset_password_code' => 'required',
        ], [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.confirmed' => 'Password does not match',
            'reset_password_code.required' => 'Reset code is required',
        ]);

        if ($validator->fails()) {
            // Return validation errors as JSON
            return response()->json(['error' => $validator->errors()], 400);
        }

        try{
            $user = User::where('email', $request->email)
                    ->where('reset_password_code', $request->reset_password_code)
                    ->first();

            if (!$user) {
                return response()->json(['message' => 'Invalid reset_password code'], 422);
            }

            // Mark the user as verified
            $user->update([
                'password' => Hash::make($request->input('password')),
                'reset_password_code' => null,
            ]);
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['token' => $token]);
        } catch (QueryException $e) {
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

