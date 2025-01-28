<?php

use App\Http\Middleware\CorsMiddleware;
use App\Http\Middleware\JsonMiddleware;
use App\Utils\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            JsonMiddleware::class,
            // Sanctum::class,
        ]);
        $middleware->appendToGroup('api', [
            JsonMiddleware::class,
            // Sanctum::class,
        ]);
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
        // $middleware->append(Cors::class);
        // $middleware->group('api', [
        //     Cors::class,
        // ]);
        // $middleware->append(Cors::class);
        // $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $exception) {
            Log::error($exception);
            $message = $exception->getMessage() ?: 'Internal Server Error';
            if ($exception instanceof ValidationException) {
                return ApiResponse::error(Response::HTTP_UNPROCESSABLE_ENTITY, $message, $exception->errors());
            }

            $code = $exception->getCode();
            if ($exception instanceof HttpExceptionInterface) {
                $code = $exception->getStatusCode();
            }
            if ($code === 0 && $exception instanceof AuthenticationException) {
                return ApiResponse::error(419, 'Session has expired');
            } else if ($code === 0) {
                $code = 500;
            }
            // if ($exception instanceof UnauthorizedException || $exception instanceof AuthenticationException) {
            //     $code = Response::HTTP_UNAUTHORIZED;
            // }

            return ApiResponse::error($code, $message);
        });
    })->create();
