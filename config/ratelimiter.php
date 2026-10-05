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
    | A list of IP addresses (IPv4 or IPv6) that are trusted reverse proxies.
    | When REMOTE_ADDR matches a trusted proxy, the X-Forwarded-For header is
    | honored to resolve the real client IP. The right-most untrusted hop in
    | the XFF chain is used to prevent header-spoofing attacks.
    |
    | Default: [] (no proxies trusted — REMOTE_ADDR is always used directly)
    |
    */
    'trusted_proxies' => [],
];
