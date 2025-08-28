<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class LogoutService
{
    public function execute() {
        /** Revoke all tokens for the authenticated user */
        Auth::user()->currentAccessToken()->delete();
        return true;
    }
}
        