<?php

declare(strict_types=1);

use Marko\RateLimiter\ClientIpKeyResolver;
use Marko\RateLimiter\Contracts\RateLimiterInterface;
use Marko\RateLimiter\Contracts\RateLimitKeyResolverInterface;
use Marko\RateLimiter\RateLimiter;

return [
    'bindings' => [
        RateLimiterInterface::class => RateLimiter::class,
        RateLimitKeyResolverInterface::class => ClientIpKeyResolver::class,
    ],
];
