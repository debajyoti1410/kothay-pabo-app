<?php

namespace App\Services\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function execute($request) {
        $remember = $request->boolean('remember_me');

        if (!Auth::attempt($request->only('email', 'password'), $remember)) {
            return [
                'status' => false,
                'data'   => null,
            ];
        }

        $authUser = Auth::user();
        /** Delete expired tokens in one query */
        $authUser->tokens()->where('expires_at', '<', now())->delete();

        /** Create new token & set expiry */
        $token = $authUser->createToken($authUser->name)->plainTextToken;
        $tokenModel = $authUser->tokens()->latest('id')->first();
        $tokenModel->update(['expires_at' => now()->addHours(2)]);

        /** Build user response with relationships eager loaded */
        $user = $authUser->only(['id', 'name', 'email']); // only needed fields
        $user['access_token'] = $token;
        $user['token_type']   = 'Bearer Token';
        $user['permissions']  = $authUser->getAllPermissions()->pluck('name');
        $user['role']         = $authUser->roles()->pluck('name');

        return [
            'status' => true,
            'data'   => $user,
        ];
    }

}
        