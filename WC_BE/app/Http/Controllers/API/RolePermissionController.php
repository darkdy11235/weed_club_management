<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
    public function assignPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        try {
            $role = Role::find($request->input('role_id'));
            $permission = Permission::find($request->input('permission_id'));

            if (!$role || !$permission) {
                return response()->json(['error' => 'Role or permission not found'], 404);
            }

            // Check if the role already has the permission
            if ($role->permissions()->where('permission_id', $permission->id)->exists()) {
                return response()->json(['message' => 'Role already has the specified permission']);
            }

            // Attach the permission to the role
            $role->permissions()->attach($permission->id);

            return response()->json(['message' => 'Permission assigned to role successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        try {
            $role = Role::find($request->input('role_id'));
            $permission = Permission::find($request->input('permission_id'));

            if (!$role || !$permission) {
                return response()->json(['error' => 'Role or permission not found'], 404);
            }

            // Detach the permission from the role
            $role->permissions()->detach($permission->id);

            return response()->json(['message' => 'Permission removed from role successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
