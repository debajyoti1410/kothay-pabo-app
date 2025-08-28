<?php

namespace App\Services\User;

use App\Models\User;

class ListUserService
{
    /**
     * Get a list of all users.
     * 
     */
    public function execute($status = null, $search = null, $paginate = false) {
        /** Get all users with their roles */
        $users = User::with('roles');

        /** If there is a search query, search for users by name or email */
        if ($search != null) {
            $users = $users->whereAny([
                'name',
                'email',
            ], 'like', '%' . $search . '%');
        } 

        /** Filter out the superadmin and system users */
        $users = $users->whereNotIn('name', ['superadmin','system'])
        ->select('id','name','email','phone','address','status')
        ->latest();

        /** Filter by status */
        if ($status) {
            $users->where('status', $status);
        }

        if ($paginate) {
            return $users->paginate(25);            
        }else {
            return $users->get();
        }

        /** Return the users as a JSON response */
        return $users;
    }
}
        