<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    protected function successResponse($data, $message = 'Success', $status = 200): JsonResponse
{
    $response = [
        'status' => 'success',
        'message' => $message,
    ];


    $response = array_merge($response, $data);

    return response()->json($response, $status);
}
}
