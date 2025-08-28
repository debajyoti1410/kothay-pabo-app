<?php

namespace App\Traits;

trait ApiResponse
{
    /** RETURN A SUCCESS JSON RESPONSE */
    public function success($data = [], $message = "Success", $code = 200) {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function error($message = "Internal Server Error", $code = 500, $errors = []) {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
        