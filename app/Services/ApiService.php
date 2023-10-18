<?php

namespace App\Services;

class ApiService
{
    public function response($data, $message = null, $code = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    public function error($message, $code = 400)
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }

    public function validationError($errors, $status = 422)
    {
        return response()->json([
            'errors' => $errors,
        ], $status);
    }
}
