<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class QueryFilter
{
    protected Builder $builder;
    protected Request $request;
    protected array $sortable = [];
    protected array $supportedParams = [
        'include',
        'filter',
        'sort',
        'page',
        'fields'
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key) && $value && in_array($key, $this->supportedParams)) {
                $this->$key($value);
            } else {
                throw new BadRequestHttpException(
                    "The '{$key}' query parameter with '{$value}' value is not supported."
                );
            }
        }

        return $this->builder;
    }

    public function include(string $value): Builder
    {
        if (method_exists($this->builder->getModel(), $value)) {
            return $this->builder->with($value);
        } else {
            throw new BadRequestHttpException(
                "The 'include' query parameter with '{$value}' value is not supported."
            );
        }

        return $this->builder;
    }

    public function filter($arr): Builder
    {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key) && $value) {
                $this->$key($value);
            } else {
                throw new BadRequestHttpException(
                    "The 'filter[{$key}]' query parameter with '{$value}' value is not supported."
                );
            }
        }

        return $this->builder;
    }

    protected function sort(string $value): void
    {
        foreach (explode(',', $value) as $attribute) {
            $direction = str_starts_with($attribute, '-') ? 'desc' : 'asc';
            $key = ltrim($attribute, '-');

            if (!in_array($key, $this->sortable) && !array_key_exists($key, $this->sortable)) {
                throw new BadRequestHttpException(
                    "The 'sort' query parameter with '{$key}' value is not supported."
                );
            }

            $this->builder->orderBy(
                $this->sortable[$key] ?? $key,
                $direction
            );
        }
    }

    public function page($arr): Builder
    {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key) && $value) {
                $this->$key($value);
            } else {
                throw new BadRequestHttpException(
                    "The 'page[{$key}]' query parameter with '{$value}' value is not supported."
                );
            }
        }

        return $this->builder;
    }

    public function fields($arr): Builder
    {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key) && $value) {
                $this->$key($value);
            } else {
                throw new BadRequestHttpException(
                    "The 'fields[{$key}]' query parameter with '{$value}' value is not supported."
                );
            }
        }

        return $this->builder;
    }
}
