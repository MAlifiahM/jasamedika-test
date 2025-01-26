<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function sendResponse($result, $message, $responseCode = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ], $responseCode);
    }

    public function sendError($error, $errorMessages = [], $responseCode = 404): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $error,
        ], $responseCode);
    }
}
