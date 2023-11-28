<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            // Return validation errors as JSON
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                $user = Auth::user();
                $token = $user->createToken('api-token')->plainTextToken;

                return response()->json(['token' => $token]);
            } else {
                throw ValidationException::withMessages(['error' => 'Invalid credentials']);
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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
