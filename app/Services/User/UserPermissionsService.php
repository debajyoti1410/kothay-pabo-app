<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Auth;

class UserPermissionsService
{
    public function execute() {
        return Auth::user()
        ->roles()
        ->with('permissions:id,name')
        ->get()
        ->pluck('permissions.*.name')
        ->flatten()
        ->unique()
        ->values()
        ->toArray();
    }
}
        