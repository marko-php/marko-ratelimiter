<?php

declare(strict_types=1);

namespace Marko\RateLimiter\Contracts;

use Marko\Routing\Http\Request;

/**
 * Resolves who a request is rate limited as (e.g. a client IP or a user ID).
 *
 * RateLimitMiddleware combines this identity with the route's bucket name, so
 * the resolver only has to answer "who", never "which route".
 */
interface RateLimitKeyResolverInterface
{
    public function resolve(
        Request $request,
    ): string;
}
