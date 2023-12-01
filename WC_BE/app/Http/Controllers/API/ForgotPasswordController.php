<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Mail\ConfirmationPasswordCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function sendResetCodeEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)
                    ->first();
        try {
            $user->update([
                'reset_password_code' => Str::random(40),
            ]);

            Mail::to($request->email)->send(new ConfirmationPasswordCodeMail($user->reset_password_code));

            return response()->json(['message' => 'Reset Code sent successfully, please check your email']);
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
