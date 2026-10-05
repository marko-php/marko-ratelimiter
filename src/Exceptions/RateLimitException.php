<?php

declare(strict_types=1);

namespace Marko\RateLimiter\Exceptions;

use Marko\Core\Exceptions\MarkoException;

class RateLimitException extends MarkoException
{
    public static function nonPositiveLimit(
        string $parameter,
        int $value,
    ): self {
        return new self(
            message: "#[RateLimit] $parameter must be a positive integer, got $value",
            context: 'While reading a #[RateLimit] attribute',
            suggestion: "Set $parameter to 1 or more, or omit it to use the ratelimiter config default",
        );
    }

    public static function emptyName(): self
    {
        return new self(
            message: '#[RateLimit] name must not be an empty string',
            context: 'While reading a #[RateLimit] attribute',
            suggestion: 'Give the bucket a name such as "login", or omit name to key the bucket by controller and action',
        );
    }
}
