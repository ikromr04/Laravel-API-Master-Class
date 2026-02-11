<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

class TicketFilter extends QueryFilter
{
    protected array $sortable = [
        'id',
        'title',
        'description',
        'status',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at'
    ];

    public function id(string $value): Builder
    {
        return $this->builder->whereIn('id', explode(',', $value));
    }

    public function title(string $value): Builder
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('title', 'like', $likeStr);
    }

    public function description(string $value): Builder
    {
        $likeStr = str_replace('*', '%', $value);

        return $this->builder->where('description', 'like', $likeStr);
    }

    public function status(string $value): Builder
    {
        return $this->builder->whereIn('status', explode(',', $value));
    }

    public function createdAt(string $value): Builder
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('created_at', $dates);
        }

        return $this->builder->whereDate('created_at', $value);
    }

    public function updatedAt(string $value)
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            return $this->builder->whereBetween('updated_at', $dates);
        }

        return $this->builder->whereDate('updated_at', $value);
    }
}
