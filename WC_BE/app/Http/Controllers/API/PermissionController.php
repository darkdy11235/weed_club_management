<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function getAllPermissions()
    {
        $permissions = Permission::all();

        return response()->json(['permissions' => $permissions]);
    }

    public function getPermissionById($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        return response()->json(['permission' => $permission]);
    }

    public function createPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions',
            'slug' => 'required|string|unique:permissions',
            'description' => 'nullable|string',
            'create' => 'required|boolean',
            'read' => 'required|boolean',
            'update' => 'required|boolean',
            'delete' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        $permission = Permission::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'create' => $request->input('create'),
            'read' => $request->input('read'),
            'update' => $request->input('update'),
            'delete' => $request->input('delete'),
        ]);

        return response()->json(['message' => 'Permission created successfully', 'permission' => $permission]);
    }

    public function updatePermission(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,' . $id,
            'slug' => 'required|string|unique:permissions,slug,' . $id,
            'description' => 'nullable|string',
            'create' => 'required|boolean',
            'read' => 'required|boolean',
            'update' => 'required|boolean',
            'delete' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error validator' => $validator->errors()], 400);
        }

        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        $permission->update([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'create' => $request->input('create'),
            'read' => $request->input('read'),
            'update' => $request->input('update'),
            'delete' => $request->input('delete'),
        ]);

        return response()->json(['message' => 'Permission updated successfully', 'permission' => $permission]);
    }

    public function deletePermission($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
