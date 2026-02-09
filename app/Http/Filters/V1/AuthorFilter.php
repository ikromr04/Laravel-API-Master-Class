<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

class AuthorFilter extends QueryFilter
{
    /**
     * List of sortable attributes.
     *
     * Keys are request parameter names; values are database columns.
     */
    protected array $sortable = [
        'id',
        'name',
        'email',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    /**
     * Include a related model.
     */
    public function include(string $value): Builder
    {
        if (method_exists($this->builder->getModel(), $value)) {
            return $this->builder->with($value);
        }

        return $this->builder;
    }

    /**
     *  Filter by author ID(s)
     */
    public function id(string $value): Builder
    {
        return $this->builder->whereIn('id', explode(',', $value));
    }

    /**
     * Filter by author name (supports * as wildcard)
     */
    public function name(string $value): Builder
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('name', 'like', $likeStr);
    }

    /**
     * Filter by author email (supports * as wildcard).
     */
    public function email(string $value): Builder
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('email', 'like', $likeStr);
    }

    /**
     * Filter by email verified date(s).
     *
     * Supports a single date or a range (comma-separated).
     */
    public function emailVerifiedAt(string $value): Builder
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('email_verified_at', $dates);
        }

        return $this->builder->whereDate('email_verified_at', $value);
    }

    /**
     * Filter by created date(s).
     *
     * Supports a single date or a range (comma-separated).
     */
    public function createdAt(string $value): Builder
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    /**
     * Filter by updated date(s).
     *
     * Supports a single date or a range (comma-separated).
     */
    public function updatedAt(string $value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }
}
