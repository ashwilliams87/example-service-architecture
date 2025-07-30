<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function() {
            Route::middleware('api')
                ->prefix('ebs')
                ->name('ebs.')
                ->group(base_path('routes/ebs.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {

    })
    ->withExceptions(function (Exceptions $exceptions) {
//        $apiResponseService = app(ResponseServiceInterface::class);

//        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) use ($apiResponseService){
//            return $apiResponseService->makeErrorResponse(405, 'Операция не поддерживается');
//        });
//
//        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) use ($apiResponseService){
//            return $apiResponseService->makeErrorResponse(405, 'Операция не поддерживается');
//        });
//
//        $exceptions->render(function (Exception $e, Request $request) use ($apiResponseService){
//            return $apiResponseService->makeErrorResponse(500, 'Внутренняя ошибка API: ' . $e->getMessage(), $e);
//        });
    })->create();
