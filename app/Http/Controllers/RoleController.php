<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\Role\RoleService;
use App\Http\Requests\Role\StoreRoleRequest;

class RoleController extends Controller
{
    use ApiResponse;

    protected $service;

    public function __construct(RoleService $service) {
        $this->service = $service;
    }

    /**
     * Fetch all roles
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        try {
            /** Get the search query */
            $search = $request->query('search');

            if (!empty($search)) {
                /** Search roles by name */
                $data = $this->service->searchRole($search);
            } else {
                /** Fetch all roles */
                $data = $this->service->getRoles();
            }

            return $this->success($data,'Roles fetch successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Role\StoreRoleRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRoleRequest $request) {
        try {
            $data = $this->service->storeRole($request);
            return $this->success([], 'Role saved successfully');
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }

    /**
     * Get all permissions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissions() {
        try {
            /** Fetch all permissions */
            $data = $this->service->getPermissions();

            /** Return a success response with the permissions data */
            $message = 'Permissions fetch successfully';
            return $this->success($data, $message);         
        } catch (\Throwable $th) {
            /** Catch any exceptions and return an error response */
            return $this->error($th->getMessage(), 500);
        }
    }
}
