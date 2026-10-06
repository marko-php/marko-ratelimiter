<?php

declare(strict_types=1);

namespace Marko\RateLimiter\Exceptions;

use Marko\Core\Exceptions\MarkoException;

class ClientIpException extends MarkoException
{
    public static function missingRemoteAddr(): self
    {
        return new self(
            message: 'Cannot resolve client IP: REMOTE_ADDR is absent',
            context: 'While resolving client IP for rate limiting',
            suggestion: 'Ensure the request has a REMOTE_ADDR set (this is unavailable in CLI contexts)',
        );
    }

    public static function invalidTrustedProxy(
        string $entry,
    ): self {
        return new self(
            message: "Invalid ratelimiter.trusted_proxies entry: $entry",
            context: 'While reading ratelimiter.trusted_proxies to resolve the client IP',
            suggestion: 'Use an IPv4 or IPv6 address (10.0.0.1, ::1) or a CIDR range (10.0.0.0/8, 2001:db8::/32) with a prefix length that fits the address family',
        );
    }

    public static function invalidIpv6Prefix(
        int $prefix,
    ): self {
        return new self(
            message: "ratelimiter.ipv6_prefix must be between 1 and 128, got $prefix",
            context: 'While bucketing an IPv6 client address for rate limiting',
            suggestion: 'Set ratelimiter.ipv6_prefix to 64 (one subscriber network, the default) or 128 to key by the full address',
        );
    }
}
