<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreUserService
{
    /** 
     *  Save or update a user in the database
     * 
     */
    public function execute($request) {
        /** Save the user in the database */
        DB::transaction(function () use($request) {
            $user = User::findOrNew($request->id);
            /** Find the user by the given id */
            if (!empty($request->id) && isset($request->role)) {
                /** If the role field is present, delete the previous role */
                DB::table('model_has_roles')->where('model_id', $user->id)->delete();
            }

            $user->name     = $request->name;
            $user->email    = $request->email;

            /** If the password field is present, hash the password */
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            
            $user->phone    = $request->phone;
            $user->address  = $request->address;
            $user->save();

            /** If the role field is present, assign the role to the user */
            if (isset($request->role)) {
                $user->assignRole($request->role);
            }
        });

        return true; 
    }
}
        