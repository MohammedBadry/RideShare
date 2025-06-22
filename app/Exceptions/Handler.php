<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        // Always return JSON for API requests
        if ($request->expectsJson() || $request->is('api/*')) {
            // Handle validation errors
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'error' => [
                        'message' => 'Validation failed',
                        'details' => $exception->errors(),
                        'code' => 422
                    ]
                ], 422);
            }

            // Handle HTTP exceptions (404, 403, etc.)
            if ($exception instanceof HttpExceptionInterface) {
                return response()->json([
                    'error' => [
                        'message' => $exception->getMessage() ?: 'HTTP Error',
                        'code' => $exception->getStatusCode()
                    ]
                ], $exception->getStatusCode());
            }

            // Fallback for other exceptions
            return response()->json([
                'error' => [
                    'message' => $exception->getMessage() ?: 'Server Error',
                    'code' => 500
                ]
            ], 500);
        }

        // Default to parent for non-API requests
        return parent::render($request, $exception);
    }
} 