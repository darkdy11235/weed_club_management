<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Get all roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllRoles()
    {
        $roles = Role::all();

        return response()->json(['roles' => $roles]);
    }

    /**
     * Create a new role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|unique:roles',
        ]);

        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        $role = Role::create([
            'role_name' => $request->input('role_name'),
        ]);

        return response()->json(['message' => 'Role created successfully', 'role' => $role]);
    }

    /**
     * Update an existing role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|unique:roles,role_name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        $role = Role::findOrFail($id);
        $role->update([
            'role_name' => $request->input('role_name'),
        ]);

        return response()->json(['message' => 'Role updated successfully', 'role' => $role]);
    }

    /**
     * Delete a role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
