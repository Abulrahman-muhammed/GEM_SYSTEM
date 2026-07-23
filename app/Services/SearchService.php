<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class SearchService
{
    public function apply(
        Builder $query,
        ?string $search,
        array $columns
    ): Builder {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($columns, $search) {

            foreach ($columns as $column) {

                if (str_contains($column, '.')) {

                    $segments = explode('.', $column);

                    $field = array_pop($segments);

                    $relation = implode('.', $segments);

                    $q->orWhereHas($relation, function ($relationQuery) use ($field, $search) {
                        $relationQuery->where($field, 'like', "%{$search}%");
                    });

                } else {

                    $q->orWhere($column, 'like', "%{$search}%");

                }
            }

        });
    }
}