<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class UserRoleController extends Controller
{
    public function assignRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        try {
            $user = User::find($request->input('user_id'));
            $role = Role::find($request->input('role_id'));

            if (!$user || !$role) {
                return response()->json(['error' => 'User or role not found'], 404);
            }

            // Check if the user already has the role
            if ($user->roles()->where('role_id', $role->id)->exists()) {
                return response()->json(['message' => 'User already has the specified role']);
            }

            // Attach the role to the user
            $user->roles()->attach($role->id);

            return response()->json(['message' => 'Role assigned successfully']);
        } catch (QueryException $e) {
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        try {
            $user = User::find($request->input('user_id'));
            $role = Role::find($request->input('role_id'));

            if (!$user || !$role) {
                return response()->json(['error' => 'User or role not found'], 404);
            }

            // Detach the role from the user
            $user->roles()->detach($role->id);

            return response()->json(['message' => 'Role removed successfully']);
        } catch (QueryException $e) {
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
