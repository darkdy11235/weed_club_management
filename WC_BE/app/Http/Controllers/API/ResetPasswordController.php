<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Validate the token
        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', Hash::make($token))
            ->first();

        if (!$reset) {
            return response()->json(['error' => 'Invalid or expired token'], 400);
        }

        // Update the user's password
        $user->password = Hash::make($password);
        $user->save();

        // Remove the token record
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }

}
