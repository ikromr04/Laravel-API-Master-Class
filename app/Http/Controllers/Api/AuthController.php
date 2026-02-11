<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Permissions\Abilities;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * Authenticate a user and issue a personal access token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->input('data.attributes');

        if (! Auth::attempt($credentials)) {
            return $this->error(
                title: 'Unauthorized',
                detail: 'The provided credentials are incorrect.',
                status: Response::HTTP_UNAUTHORIZED
            );
        }

        $user = $request->user();

        $token = $user->createToken('api:default', Abilities::getAbilities($user));

        return $this->success(
            data: [
                'type' => 'tokens',
                'id' => (string) $token->accessToken->id,
                'attributes' => [
                    'token' => $token->plainTextToken,
                    'userId' => (string) $user->id
                ],
            ],
            selfLink: route('login'),
        );
    }


    /**
     * Log out the authenticated user.
     */
    public function logout(Request $request): JsonResponse|Response
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
