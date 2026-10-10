<?php

namespace App\Http\Traits;

trait ApiResponse
{
    protected function success($data = null, string $message = 'OK', int $httpStatus = 200)
    {
        return response()->json([
            'code' => 0,
            'message' => $message,
            'data' => $data,
        ], $httpStatus);
    }

    protected function error(string $message, int $code = 4000, int $httpStatus = 400)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => null,
        ], $httpStatus);
    }

    protected function paginate($paginator, $extra = [])
    {
        return $this->success([
            'list' => $paginator->items(),
            'page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            ...$extra,
        ]);
    }
}
