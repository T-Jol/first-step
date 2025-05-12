<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use App\Http\Responses\ApiResponse;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // Gestion des erreurs 404 (Not Found)
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return ApiResponse::error('Resource not found', 404);
            }
        });

        // Gestion des erreurs de modèle non trouvé
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return ApiResponse::error('Resource not found', 404);
            }
        });

        // Gestion des erreurs de validation
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return ApiResponse::error(
                    'Validation failed',
                    422,
                    $e->errors()
                );
            }
        });

        // Gestion des erreurs d'authentification
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return ApiResponse::error('Unauthenticated', 401);
            }
        });

        // Gestion des erreurs de base de données
        $this->renderable(function (QueryException $e, $request) {
            if ($request->expectsJson()) {
                return ApiResponse::error(
                    'Database error occurred',
                    500
                );
            }
        });

        // Gestion des erreurs générales
        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                return ApiResponse::error(
                    $e->getMessage(),
                    $status
                );
            }
        });
    }
} 