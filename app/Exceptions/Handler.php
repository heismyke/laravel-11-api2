<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ErrorResponse) {
            // Log the error message
            Log::error("ErrorResponse: Status Code: {$exception->getStatusCode()}, Message: {$exception->getMessage()}");

            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }

        // Log general exceptions
        Log::error("General Exception: {$exception->getMessage()}");

        return response()->json([
            'success' => false,
            'error' => $exception->getMessage() ?: 'Server Error',
        ], 500);
    }
}
