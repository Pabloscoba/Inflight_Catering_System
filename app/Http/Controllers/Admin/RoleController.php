<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of roles with their permissions
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        $allPermissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.roles.edit', compact('role', 'allPermissions', 'rolePermissions'));
    }

    /**
     * Update the specified role's permissions
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Get permission IDs or empty array if none selected
        $permissionIds = $request->input('permissions', []);
        
        // Get permission objects
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        
        // Sync permissions (this will remove old ones and add new ones)
        $role->syncPermissions($permissions);
        
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Log the activity
        activity('role-management')
            ->causedBy(auth()->user())
            ->performedOn($role)
            ->withProperties([
                'role' => $role->name,
                'permissions_count' => $permissions->count(),
                'permissions' => $permissions->pluck('name')->toArray(),
            ])
            ->log("Updated permissions for role '{$role->name}'");

        return redirect()->route('admin.roles.index')
            ->with('success', "Permissions for role '{$role->name}' updated successfully. Total: {$permissions->count()} permissions.");
    }

    /**
     * Show the form for assigning roles to users
     */
    public function assignForm()
    {
        $users = \App\Models\User::with('roles')->get();
        $roles = Role::with('permissions')->get();
        
        return view('admin.roles.assign', compact('users', 'roles'));
    }

    /**
     * Assign a role to a user
     */
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = \App\Models\User::findOrFail($userId);
        $role = Role::findOrFail($request->role_id);
        
        // Remove all existing roles and assign the new one
        $user->syncRoles([$role->name]);

        return redirect()->route('admin.roles.assign')->with('success', "Role '{$role->name}' assigned to {$user->name} successfully");
    }
}
