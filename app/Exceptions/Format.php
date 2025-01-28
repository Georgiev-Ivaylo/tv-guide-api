<?php

namespace App\Exceptions;

use App\Utils\ApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Format extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        $code = $exception->getCode() ?: 500;
        $message = $exception->getMessage() ?: 'Internal Server Error';

        return ApiResponse::error($code, $message);
    }
}