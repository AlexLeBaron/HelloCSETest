<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $exception): Response
    {
        // Erreur de validation
        if ($exception instanceof ValidationException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid data',
                'errors' => $exception->errors(),
            ], 422);
        }

        // Erreur d'authentication
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        // Erreur de type non trouve
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Route not found',
            ], 404);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not found',
            ], 404);
        }

        // Erreur interne
        return response()->json([
            'status' => 'error',
            'message' => 'Internal error occured',
            'errors' => $exception->getMessage(),
        ], 500);
    }

    public function report(Throwable $exception): void
    {
        if ($this->shouldReport($exception)) {
            logger()->error('An exception has occured : ' . $exception->getMessage(), [
                'exception' => get_class($exception),
                'trace' => $exception->getTraceAsString(),
            ]);
        }
    }
}
