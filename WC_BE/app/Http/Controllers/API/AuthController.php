<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validation logic...

        $user = // Create user logic...

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    public function login(Request $request)
    {
        // Validation logic...

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials']);
        }

        $token = $request->user()->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token]);
    }
}
