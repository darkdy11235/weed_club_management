<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()->json(['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'phone' => 'required|string|unique:users',
            'address' =>'required|string',
            'email' =>'required|email|unique:users',
            'password' => 'required|min:8',
            'avatar' =>'string'
        ]);

        $user = User::create($request->all());

        return response()->json(['user' => $user, 'message' => 'User created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'string',
        'age' => 'integer',
        'gender' => 'string',
        'phone' => 'string',
        'address' => 'string',
        'email' => [
            'email',
            Rule::unique('users')->ignore($user->id),
        ],
        'password' => 'min:8',
        'avatar' => 'string',
        'remember_token' => 'string',
        'password_reset_token' => 'string',
        'password_reset_token_expiry' => 'string',
        'status' => 'string',
        'created_by' => 'string',
    ]);

    // Hash the password if provided
    if ($request->filled('password')) {
        $request->merge(['password' => Hash::make($request->input('password'))]);
    }

    $user->update($request->all());

    return response()->json(['user' => $user, 'message' => 'User updated successfully'], 200);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
