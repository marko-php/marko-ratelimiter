<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Limits
    |--------------------------------------------------------------------------
    |
    | Applied by RateLimitMiddleware to any route without a #[RateLimit]
    | attribute, and to any limit a #[RateLimit] attribute leaves unset:
    | at most default_max_attempts requests per default_decay_seconds
    | window, per route and client.
    |
    */
    'default_max_attempts' => 60,
    'default_decay_seconds' => 60,

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies
    |--------------------------------------------------------------------------
    |
    | A list of trusted reverse proxies: IP addresses (IPv4 or IPv6) or CIDR
    | ranges such as '10.0.0.0/8' or '2001:db8::/32'. When REMOTE_ADDR matches
    | a trusted proxy, the X-Forwarded-For header is honored to resolve the
    | real client IP. The right-most untrusted hop in the XFF chain is used
    | to prevent header-spoofing attacks. An invalid entry throws.
    |
    | Default: [] (no proxies trusted — REMOTE_ADDR is always used directly)
    |
    */
    'trusted_proxies' => [],

    /*
    |--------------------------------------------------------------------------
    | IPv6 Prefix
    |--------------------------------------------------------------------------
    |
    | IPv6 clients are rate limited per network rather than per address: one
    | subscriber usually holds a whole /64, so keying by the full address
    | would let a client rotate through 2^64 fresh buckets. Set to 128 to key
    | by the full address, or lower (e.g. 56, 48) to group wider allocations.
    |
    */
    'ipv6_prefix' => 64,
];
