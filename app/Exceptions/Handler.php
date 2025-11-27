<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
    }

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {

            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => __('errors.validation_failed'),
                    'errors'  => $e->errors(),
                ], 422);
            }

            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'message' => __('errors.unauthenticated'),
                ], 401);
            }

            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'message' => __('errors.forbidden'),
                ], 403);
            }

            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => __('errors.not_found'),
                ], 404);
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'message' => __('errors.not_found'),
                ], 404);
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'message' => __('errors.method_not_allowed'),
                ], 405);
            }

            if ($e instanceof HttpExceptionInterface) {
                return response()->json([
                    'message' => $e->getMessage() ?: __('errors.server_error'),
                ], $e->getStatusCode());
            }

            return response()->json([
                'message' => __('errors.server_error'),
                'error'   => $e->getMessage(),
            ], 500);
        }

        // لو مش طلب API (مثلاً web عادي) خليه يتعامل بالطريقة الافتراضية
        return parent::render($request, $e);
    }
}
