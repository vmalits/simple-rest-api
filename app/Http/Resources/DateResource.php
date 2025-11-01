<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DateResource extends JsonResource
{
    /** @return array<string,string> */
    public function toArray(Request $request): array
    {
        return [
            'human'  => $this->resource->diffForHumans(),
            'string' => $this->resource->toDateTimeString(),
        ];
    }
}
