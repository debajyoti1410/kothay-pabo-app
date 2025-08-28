<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\Auth\LoginService;
use App\Services\Auth\LogoutService;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request,LoginService $service) {
        try {
            $response = $service->execute($request);
            if ($response['status']) {
                return $this->success(['user' => $response['data']],'Login Success',200);                            
            }else {
                return $this->error("Email or password field is incorrect",401);
            }
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function checkLogin() {
        return $this->error("Unauthenticated.",401);
    }

    public function logout(LogoutService $service) {
        try {
            $service->execute();
            /** Return a success response indicating logout */
            return $this->success(['message' => 'Logout Successfully']);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
