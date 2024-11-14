<?php

namespace App\Services;

use App\Exceptions\CustomError;
use App\Exceptions\ErrorResponse;

class RequestHandlerService
{
    public function asyncHandler(callable $callback)
    {
        try {
            return $callback();
        } catch (ErrorResponse $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
