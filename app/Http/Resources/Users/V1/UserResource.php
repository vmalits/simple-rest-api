<?php

declare(strict_types=1);

namespace App\Http\Resources\Users\V1;

use App\Http\Resources\DateResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    /** @return array<string,mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->resource->id,
            'name'  => $this->resource->name,
            'email' => [
                'address'  => $this->resource->email,
                'verified' => $this->resource->hasVerifiedEmail(),
            ],
            'created_at' => new DateResource(
                resource: $this->resource->created_at,
            ),
        ];
    }
}
