<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof TokenMismatchException) {
            return response()->view('errors.419', [
                'message' => 'Halaman ini telah kadaluarsa. Silakan muat ulang dan coba lagi.'
            ], 419);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.404', [
                'message' => 'Data yang Anda cari tidak ditemukan.'
            ], 404);
        }

        if ($exception instanceof AuthenticationException) {
            return redirect()->route('login')->withErrors([
                'msg' => 'Sesi Anda telah berakhir. Silakan login kembali.'
            ]);
        }

        if ($exception instanceof AccessDeniedHttpException) {
            return response()->view('errors.403', [
                'message' => $exception->getMessage() ?: 'Anda tidak memiliki akses ke halaman ini.'
            ], 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [
                'message' => 'Halaman yang Anda cari tidak ditemukan.'
            ], 404);
        }

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            
            if ($statusCode === 403) {
                return response()->view('errors.403', [
                    'message' => $exception->getMessage() ?: 'Anda tidak memiliki akses ke halaman ini.'
                ], 403);
            }
            
            if ($statusCode === 404) {
                return response()->view('errors.404', [
                    'message' => $exception->getMessage() ?: 'Halaman yang Anda cari tidak ditemukan.'
                ], 404);
            }
            
            if ($statusCode === 500) {
                return response()->view('errors.500', [
                    'message' => 'Terjadi kesalahan pada server.'
                ], 500);
            }
        }

        return parent::render($request, $exception);
    }
}