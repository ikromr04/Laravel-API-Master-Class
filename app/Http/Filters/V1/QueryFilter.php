<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Builder $builder;
    protected Request $request;
    protected array $sortable = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply request-based filters to the query builder.
     *
     * Iterates over all request parameters and invokes
     * filter methods that exist on the concrete filter class.
     *
     * Example:
     *  /users?status=active&sort=-created_at
     *
     * @param  Builder  $builder  The Eloquent query builder.
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key) && $value) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    /**
     * Apply filters from an explicit array.
     *
     * Useful when filters do not come directly
     * from the HTTP request (e.g. internal logic).
     *
     *
     * Example:
     *  /users?filter[status]=active
     *
     * @param  array<string, mixed>  $arr  Filter key-value pairs.
     * @return Builder
     */
    public function filter($arr): Builder
    {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    /**
     * Apply sorting to the query builder.
     *
     * Supports multiple fields separated by commas.
     * Prefix a field with "-" to sort descending.
     *
     * Example:
     *  ?sort=name,-created_at
     *
     * @param  string  $value  Sort query string.
     * @return void
     */
    protected function sort(string $value): void
    {
        foreach (explode(',', $value) as $attribute) {
            $direction = str_starts_with($attribute, '-') ? 'desc' : 'asc';
            $key = ltrim($attribute, '-');

            if (!in_array($key, $this->sortable) && !array_key_exists($key, $this->sortable)) {
                continue;
            }

            $this->builder->orderBy(
                $this->sortable[$key] ?? $key,
                $direction
            );
        }
    }
}
