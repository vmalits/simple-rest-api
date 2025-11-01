<?php

declare(strict_types=1);

namespace App\Http\Resources\Users\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

/** @see \App\Models\User */
class UserCollection extends ResourceCollection
{
    /**
     * @var string
     */
    public $collects = UserResource::class;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data'  => $this->collection,
            'meta'  => $this->meta(),
            'links' => $this->links(),
        ];
    }

    /**
     * @return array<string, int|string|null>
     */
    protected function meta(): array
    {
        if (! $this->resource instanceof AbstractPaginator) {
            return [];
        }

        return [
            'current_page' => $this->resource->currentPage(),
            'from'         => $this->resource->firstItem(),
            'last_page'    => $this->resource->lastPage(),
            'path'         => $this->resource->path(),
            'per_page'     => $this->resource->perPage(),
            'to'           => $this->resource->lastItem(),
            'total'        => $this->resource->total(),
        ];
    }

    /**
     * @return array<string, string|null>
     */
    protected function links(): array
    {
        if (! $this->resource instanceof AbstractPaginator) {
            return [];
        }

        return [
            'first' => $this->resource->url(1),
            'last'  => $this->resource->url($this->resource->lastPage()),
            'prev'  => $this->resource->previousPageUrl(),
            'next'  => $this->resource->nextPageUrl(),
        ];
    }
}
