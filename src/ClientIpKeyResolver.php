<?php

declare(strict_types=1);

namespace Marko\RateLimiter;

use Marko\Config\Exceptions\ConfigNotFoundException;
use Marko\RateLimiter\Contracts\RateLimitKeyResolverInterface;
use Marko\RateLimiter\Exceptions\ClientIpException;
use Marko\Routing\Http\Request;

/**
 * Default rate limit identity: the real client IP, honouring trusted proxies.
 */
readonly class ClientIpKeyResolver implements RateLimitKeyResolverInterface
{
    public function __construct(
        private ClientIpResolver $clientIpResolver,
    ) {}

    /**
     * @throws ClientIpException|ConfigNotFoundException
     */
    public function resolve(
        Request $request,
    ): string {
        return $this->clientIpResolver->resolve($request);
    }
}
