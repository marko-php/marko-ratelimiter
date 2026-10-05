<?php

declare(strict_types=1);

use Marko\RateLimiter\ClientIpKeyResolver;
use Marko\RateLimiter\ClientIpResolver;
use Marko\RateLimiter\Contracts\RateLimiterInterface;
use Marko\RateLimiter\Contracts\RateLimitKeyResolverInterface;
use Marko\RateLimiter\RateLimiter;
use Marko\Routing\Http\Request;
use Marko\Testing\Fake\FakeConfigRepository;

describe('ClientIpKeyResolver', function (): void {
    it('implements RateLimitKeyResolverInterface', function (): void {
        $resolver = new ClientIpKeyResolver(new ClientIpResolver(new FakeConfigRepository([
            'ratelimiter.trusted_proxies' => [],
        ])));

        expect($resolver)->toBeInstanceOf(RateLimitKeyResolverInterface::class);
    });

    it('resolves the rate limit identity from the client ip', function (): void {
        $resolver = new ClientIpKeyResolver(new ClientIpResolver(new FakeConfigRepository([
            'ratelimiter.trusted_proxies' => ['10.0.0.1'],
        ])));

        $request = new Request(server: [
            'REMOTE_ADDR' => '10.0.0.1',
            'HTTP_X_FORWARDED_FOR' => '2001:db8::1',
        ]);

        expect($resolver->resolve($request))->toBe('2001:db8::1');
    });
});

describe('module bindings', function (): void {
    it('binds RateLimitKeyResolverInterface to ClientIpKeyResolver', function (): void {
        $module = require dirname(__DIR__, 2) . '/module.php';

        expect($module['bindings'])->toBe([
            RateLimiterInterface::class => RateLimiter::class,
            RateLimitKeyResolverInterface::class => ClientIpKeyResolver::class,
        ]);
    });
});
