<?php

namespace App\Services\Role;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService
{
    /**
     * Retrieve all roles with permissions.
     */
    public function getRoles() {
        // If the user is a super admin, show all roles except the super admin role
        // Otherwise, show all roles except the super admin and admin role
        $roleFilter = auth()->user()->name === 'superadmin'
            ? ['Super Admin']
            : ['Super Admin', 'Admin'];

        $data = Role::with('permissions')
            ->whereNotIn('name', $roleFilter)
            ->latest()
            ->paginate(15);

        return $data;
    }

    /**
     * Retrieve roles with permissions with a search query.
     */
    public function searchRole($search) {
        /** Build the query */
        $roles = Role::with('permissions');

        /** Apply the search query if it is not null */
        if ($search != null) {
            $roles = $roles->where('name', 'like', '%' . $search . '%');
        }

        /** Exclude the Super Admin role */
        $roles = $roles->where('name', '!=', 'Super Admin');

        /** If the user is not a super admin, exclude the admin role too */
        if (!auth()->user()->hasRole('Super Admin')) {
            $roles = $roles->where('name', '!=', 'Admin');
        }

        /** Order by latest and paginate */
        $roles = $roles->latest()->paginate(15);

        return $roles;
    }

    /**
     * Store a new role in the database.
     */
    public function storeRole($request) {
        DB::transaction(function () use ($request) {
            $role = $request->id ? Role::findOrFail($request->id) : new Role();

            /** Set the role name and guard_name */
            $role->name = $request->name;
            $role->guard_name = 'web';
            $role->save();

            /** Process the permission data */
            $permissions = collect($request->permission ?? [])
                ->map(fn($item) => (int) ($item['id'] ?? $item))
                ->filter()
                ->values();

            /** Sync the permissions if there are any */
            if ($permissions->isNotEmpty()) {
                $role->syncPermissions($permissions);
            }
        });

        return true;
    }

    /**
     * Get all permissions from the database.
     */
    public function getPermissions() {
        /** Fetch all permissions from the database */
        $permissions = Permission::all();
        return $permissions;
    }
}
        