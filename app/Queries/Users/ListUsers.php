<?php

declare(strict_types=1);

namespace App\Queries\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ListUsers
{
    public function handle(Builder|string|null $query = null): Builder
    {
        return QueryBuilder::for(
            subject: $query ?? User::query(),
        )
            ->allowedFilters(
                filters: [
                    AllowedFilter::exact(name: 'name'),
                    AllowedFilter::exact(name: 'email')
                ]
            )
            ->allowedSorts(sorts: ['name'])
            ->getEloquentBuilder();
    }
}