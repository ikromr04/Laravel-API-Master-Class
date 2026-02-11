<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'jsonapi' => ['version' => '1.1'],
                'errors' => [[
                    'title' => 'Unauthenticated',
                    'detail' => 'A valid access token must be provided to perform this request.',
                    'status' => Response::HTTP_UNAUTHORIZED,
                ]]
            ], Response::HTTP_UNAUTHORIZED);
        });

        $exceptions->render(function (BadRequestHttpException $e, Request $request) {
            return response()->json([
                'jsonapi' => ['version' => '1.1'],
                'errors' => [[
                    'title' => 'Bad Request',
                    'detail' => $e->getMessage(),
                    'status' => Response::HTTP_BAD_REQUEST,
                ]]
            ], Response::HTTP_BAD_REQUEST);
        });
    })->create();
