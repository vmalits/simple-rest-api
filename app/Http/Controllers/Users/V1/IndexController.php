<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\V1\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('Users', 'Endpoints related to user management.')]
final class IndexController extends Controller
{
    #[Endpoint('List users', 'Get a paginated list of users.')]
    #[Authenticated]
    #[QueryParam('per_page', 'integer', 'Number of users per page (1–100).', example: 10)]
    #[QueryParam('filter[name]', 'string', 'Filter users by exact name.', example: 'John Doe')]
    #[QueryParam('filter[email]', 'string', 'Filter users by exact email.', example: 'john@example.com')]
    #[QueryParam('sort', 'string', 'Sort by name (e.g. "sort=name" or "sort=-name").', example: 'name')]
    #[Response([
        'data' => [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'created_at' => '2025-10-27T12:00:00Z',
                'updated_at' => '2025-10-27T12:00:00Z',
            ],
        ],
        'meta' => [
            'current_page' => 1,
            'last_page' => 10,
            'per_page' => 10,
            'total' => 100,
        ],
    ], description: 'Successful response with paginated users.')]
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
