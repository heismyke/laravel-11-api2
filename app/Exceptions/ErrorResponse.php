<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;
use Exception;

class ErrorResponse extends Exception
{
    protected $statusCode;

    public function __construct($message, $statusCode)
    {
        parent::__construct($message); // Call the parent constructor with the message
        $this->statusCode = $statusCode;

        // Log the error message and status code
        Log::error("ErrorResponse: Status Code: {$this->statusCode}, Message: {$this->getMessage()}");
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}