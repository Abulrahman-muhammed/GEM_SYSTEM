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
        if (!$search) {
            return $query;
        }

        $query->where(function ($q) use ($search, $columns) {

            foreach ($columns as $column) {

                if (str_contains($column, '.')) {

                    [$relation, $field] = explode('.', $column);

                    $q->orWhereHas($relation, function ($r) use ($field, $search) {
                        $r->where($field, 'like', "%{$search}%");
                    });

                } else {

                    $q->orWhere($column, 'like', "%{$search}%");

                }

            }

        });

        return $query;
    }
}