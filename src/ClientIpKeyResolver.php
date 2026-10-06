<?php

declare(strict_types=1);

namespace Marko\RateLimiter;

use Marko\Config\Exceptions\ConfigNotFoundException;
use Marko\RateLimiter\Config\RateLimiterConfig;
use Marko\RateLimiter\Contracts\RateLimitKeyResolverInterface;
use Marko\RateLimiter\Exceptions\ClientIpException;
use Marko\RateLimiter\Support\IpAddress;
use Marko\Routing\Http\Request;

/**
 * Default rate limit identity: the real client IP, honouring trusted proxies.
 *
 * IPv6 clients are bucketed by network prefix (ratelimiter.ipv6_prefix, /64 by
 * default): a single subscriber is routinely handed a whole /64, so keying by
 * the full address would let one client rotate through 2^64 fresh buckets.
 */
readonly class ClientIpKeyResolver implements RateLimitKeyResolverInterface
{
    public function __construct(
        private ClientIpResolver $clientIpResolver,
        private RateLimiterConfig $rateLimiterConfig,
    ) {}

    /**
     * @throws ClientIpException|ConfigNotFoundException
     */
    public function resolve(
        Request $request,
    ): string {
        $ip = $this->clientIpResolver->resolve($request);
        $packed = IpAddress::pack($ip);

        if ($packed === null) {
            return $ip;
        }

        if (strlen($packed) === 4) {
            return (string) inet_ntop($packed);
        }

        $prefixLength = $this->rateLimiterConfig->ipv6Prefix();

        return inet_ntop(IpAddress::mask($packed, $prefixLength)) . '/' . $prefixLength;
    }
}
