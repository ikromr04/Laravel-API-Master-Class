<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponses
{
    /**
     * Return a successful JSON response with HTTP 200 status.
     *
     * Shortcut helper for common successful responses.
     *
     * @param  string  $message  Human-readable success message.
     * @param  mixed   $data     Optional response payload.
     * @return JsonResponse
     */
    public function ok(string $message = 'Success', mixed $data = null): JsonResponse
    {
        return $this->success($message, $data, Response::HTTP_OK);
    }

    /**
     * Return a successful JSON response.
     *
     * This method is used internally to build standardized
     * success responses with a customizable HTTP status code.
     *
     * @param  string  $message     Human-readable success message.
     * @param  mixed   $data        Optional response payload.
     * @param  int     $statusCode  HTTP status code.
     * @return JsonResponse
     */
    protected function success(
        string $message = 'Success',
        mixed $data = null,
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        return response()->json([
            'message' => $message,
            'success' => true,
            'data'    => $data,
        ], $statusCode);
    }

    /**
     * Return a 404 Not Found JSON response.
     *
     * @param  string  $message  Error message.
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFound(string $message = 'Not Found.'): JsonResponse
    {
        return response()->json(['message' => $message], Response::HTTP_NOT_FOUND);
    }

    /**
     * Return a JSON error response.
     *
     * @param  string  $message     Error message.
     * @param  int     $statusCode  HTTP status code.
     * @return JsonResponse
     */
    protected function error($message, $statusCode)
    {
        return response()->json([
            'message' => $message,
        ], $statusCode);
    }
}
