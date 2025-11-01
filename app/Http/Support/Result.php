<?php

declare(strict_types=1);

namespace App\Http\Support;

use Throwable;

final readonly class Result
{
    public function __construct(
        public mixed $value = null,
        public ?Throwable $error = null,
    ) {
    }

    public static function ok(mixed $value): self
    {
        return new self(
            value: $value,
            error: null,
        );
    }

    public static function error(mixed $error): self
    {
        return new self(
            value: null,
            error: $error,
        );
    }

    public function isOk(): bool
    {
        return $this->error === null;
    }

    public function isError(): bool
    {
        return $this->error !== null;
    }

    /**
     * @throws Throwable
     */
    public function unwrap(): mixed
    {
        if ($this->error !== null) {
            throw $this->error;
        }

        return $this->value;
    }
}
