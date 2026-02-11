<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait ApiResponse
 *
 * Provides standardized JSON:API (v1.1) compliant
 * success and error responses for REST APIs.
 *
 * This trait enforces:
 * - A top-level `jsonapi` object
 * - Proper `data` and `errors` members
 * - Correct HTTP status codes
 *
 * @see https://jsonapi.org/format/
 */
trait ApiResponses
{
    /**
     * Return a successful JSON:API response.
     *
     * Example response:
     * {
     *   "jsonapi": { "version": "1.1" },
     *   "data": {...}
     * }
     *
     * @param  mixed $data The primary resource object or resource collection.
     * @param  int   $status HTTP status code (default: 200 OK).
     * @param  string|null $selfLink  Optional "self" link for the current resource.
     *
     * @return JsonResponse
     */
    protected function success(
        mixed $data,
        int $status = Response::HTTP_OK,
        ?string $selfLink = null
    ): JsonResponse {
        $response = [
            'jsonapi' => ['version' => '1.1'],
            'data' => $data,
        ];

        if ($selfLink) {
            $response['links']['self'] = $selfLink;
        }

        return response()->json($response, $status);
    }

    /**
     * Return a JSON:API compliant error response.
     *
     * Example response:
     * {
     *   "jsonapi": { "version": "1.1" },
     *   "errors": [
     *     {
     *       "status": "403",
     *       "title": "Forbidden",
     *       "detail": "You are not authorized to perform this action."
     *     }
     *   ]
     * }
     *
     * @param  string $title A short, human-readable summary of the error.
     * @param  string $detail A human-readable explanation specific to this occurrence.
     * @param  int    $status HTTP status code.
     *
     * @return JsonResponse
     */
    public function error(
        string $title,
        string $detail,
        int $status
    ): JsonResponse {
        return response()->json([
            'jsonapi' => ['version' => '1.1'],
            'errors' => [[
                'status' => (string) $status,
                'title'  => $title,
                'detail' => $detail,
            ]],
        ], $status);
    }
}
