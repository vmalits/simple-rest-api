<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\V1\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class IndexController extends Controller
{
    public function __invoke(Request $request): UserCollection
    {
        $perPage = (int)$request->integer('per_page', 10);
        $perPage = max(1, min($perPage, 100));

        return new UserCollection(
            resource: QueryBuilder::for(User::class)
                ->allowedFilters([
                    AllowedFilter::exact('name'),
                    AllowedFilter::exact('email')
                ])
                ->allowedSorts('name')
                ->paginate($perPage)
                ->withQueryString()
        );
    }
}
