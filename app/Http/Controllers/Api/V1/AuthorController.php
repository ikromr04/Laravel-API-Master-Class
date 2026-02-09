<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\ReplaceAuthorRequest;
use App\Models\User;
use App\Http\Requests\Api\V1\StoreAuthorRequest;
use App\Http\Requests\Api\V1\UpdateAuthorRequest;
use App\Http\Resources\V1\AuthorCollection;
use App\Http\Resources\V1\AuthorResource;
use Illuminate\Http\JsonResponse;

class AuthorController extends ApiController
{
    /**
     * List authors with optional filtering.
     */
    public function index(AuthorFilter $filter): AuthorCollection
    {
        $authors = User::filter($filter)->get();

        return new AuthorCollection($authors);
    }

    /**
     * Create a new author.
     */
    public function store(StoreAuthorRequest $request): AuthorResource
    {
        $user = User::create($request->mappedAttributes());

        return new AuthorResource($user);
    }

    /**
     * Show a single author.
     */
    public function show(AuthorFilter $filter, string $authorId): AuthorResource|JsonResponse
    {
        $author = User::filter($filter)->find($authorId);
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        return new AuthorResource($author);
    }

    /**
     * Replace an existing author.
     */
    public function replace(ReplaceAuthorRequest $request, string $authorId): AuthorResource|JsonResponse
    {
        $author = User::find($authorId);
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        $author->update($request->mappedAttributes());

        return new AuthorResource($author);
    }

    /**
     * Update an existing author.
     */
    public function update(UpdateAuthorRequest $request, string $authorId): AuthorResource|JsonResponse
    {
        $author = User::find($authorId);
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        $author->update($request->mappedAttributes());

        return new AuthorResource($author);
    }

    /**
     * Delete an author.
     */
    public function destroy(string $authorId): JsonResponse
    {
        $author = User::find($authorId);
        if (! $author) {
            return $this->notFound('Author cannot be found.');
        }

        $author->delete();

        return $this->ok('Author successfully deleted.');
    }
}
