<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    protected $code = 4009;

    public function __construct(string $message = '', int $code = 4009, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        return response()->json([
            'code' => $this->code,
            'message' => $this->getMessage(),
            'data' => null,
        ], 200);
    }
}
