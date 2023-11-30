<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' =>'required|email',
            'password' => 'required|string|confirmed',
            'password' => 'required|min:6|confirmed',
        ],
        [
            'email.required' => 'Email is required.',
            'email.email' => 'Email is invalid.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Password does not match.',
        ]);

        // Check for validation errors
        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['message' => 'Password reset successfully.'], 200);
        } else {
            return response()->json(['message' => 'We can\'t find a user with that e-mail address.'], 404);
        }
        return response()->json(['message' => 'We can\'t find a user with that e-mail address.'], 404);
    }
}
