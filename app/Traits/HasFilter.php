<?php

namespace App\Traits;

use App\Http\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

trait HasFilter
{
    /**
     * Apply a query filter to the Eloquent builder.
     *
     * This local scope allows models to delegate query
     * filtering logic to a dedicated QueryFilter class.
     *
     * Usage:
     *  Model::filter($filters)->get();
     *
     * @param  Builder     $builder  The Eloquent query builder instance.
     * @param  QueryFilter $filter   The filter object responsible for
     *                               applying constraints to the query.
     * @return Builder
     */
    public function scopeFilter(Builder $builder, QueryFilter $filter): Builder
    {
        return $filter->apply($builder);
    }
}
