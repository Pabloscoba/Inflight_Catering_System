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
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissions = Permission::whereIn('id', $request->permissions ?? [])->get();
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Role permissions updated successfully');
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
