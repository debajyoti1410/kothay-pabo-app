<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\User\ListUserService;
use App\Services\User\StoreUserService;
use App\Http\Requests\User\StoreUserRequest;
use App\Services\User\UserPermissionsService;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\User\ListUserService  $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListUserService $service) {
        try {
            $search = $request->query('search');
            $status = $request->query('status');
            $paginate = $request->query('paginate') == "true" ?  true : false;
            $data = $service->execute($status, $search, $paginate);

            /** Return the response */
            return $this->success($data,'Users fetch successfully');
        } catch (\Throwable $th) {
            /** Return the error */
            return $this->error($th->getMessage(), 500);
        }
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services\User\StoreUserService  $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request, StoreUserService $service) {
        try {
            $data = $service->execute($request);
            return $this->success($data,'Users Store successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }    
    
    /**
     * Get all the permissions for the currently logged in user.
     *
     * @param  \App\Services\User\UserPermissionsService  $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissions(UserPermissionsService $service) {
        try {
            $permissions = $service->execute();
            $data = compact('permissions');
            return $this->success($data,'Permissions Fetched Successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }    
}
