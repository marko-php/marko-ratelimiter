<?php

declare(strict_types=1);

use Marko\RateLimiter\ClientIpKeyResolver;
use Marko\RateLimiter\ClientIpResolver;
use Marko\RateLimiter\Config\RateLimiterConfig;
use Marko\RateLimiter\Contracts\RateLimiterInterface;
use Marko\RateLimiter\Contracts\RateLimitKeyResolverInterface;
use Marko\RateLimiter\RateLimiter;
use Marko\Routing\Http\Request;
use Marko\Testing\Fake\FakeConfigRepository;

function createKeyResolver(
    array $trustedProxies = [],
    int $ipv6Prefix = 64,
): ClientIpKeyResolver {
    $config = new FakeConfigRepository([
        'ratelimiter.trusted_proxies' => $trustedProxies,
        'ratelimiter.ipv6_prefix' => $ipv6Prefix,
    ]);

    return new ClientIpKeyResolver(new ClientIpResolver($config), new RateLimiterConfig($config));
}

describe('ClientIpKeyResolver', function (): void {
    it('implements RateLimitKeyResolverInterface', function (): void {
        expect(createKeyResolver())->toBeInstanceOf(RateLimitKeyResolverInterface::class);
    });

    it('resolves the rate limit identity from the client ip', function (): void {
        $request = new Request(server: [
            'REMOTE_ADDR' => '10.0.0.1',
            'HTTP_X_FORWARDED_FOR' => '203.0.113.50',
        ]);

        expect(createKeyResolver(['10.0.0.1'])->resolve($request))->toBe('203.0.113.50');
    });

    it('buckets IPv6 clients by their /64 network by default', function (): void {
        $request = new Request(server: [
            'REMOTE_ADDR' => '10.0.0.1',
            'HTTP_X_FORWARDED_FOR' => '2001:db8::1',
        ]);

        expect(createKeyResolver(['10.0.0.1'])->resolve($request))->toBe('2001:db8::/64');
    });

    it('gives every address in one IPv6 /64 the same key so rotation cannot bypass the limit', function (): void {
        $resolver = createKeyResolver();
        $first = new Request(server: ['REMOTE_ADDR' => '2001:db8:aa:bb::1']);
        $rotated = new Request(server: ['REMOTE_ADDR' => '2001:db8:aa:bb:dead:beef:cafe:f00d']);
        $otherNetwork = new Request(server: ['REMOTE_ADDR' => '2001:db8:aa:bc::1']);

        expect($resolver->resolve($first))->toBe('2001:db8:aa:bb::/64')
            ->and($resolver->resolve($rotated))->toBe($resolver->resolve($first))
            ->and($resolver->resolve($otherNetwork))->not->toBe($resolver->resolve($first));
    });

    it('buckets IPv6 clients by the configured ipv6_prefix', function (): void {
        $request = new Request(server: ['REMOTE_ADDR' => '2001:db8:aa:bbcc::1']);

        expect(createKeyResolver(ipv6Prefix: 56)->resolve($request))->toBe('2001:db8:aa:bb00::/56')
            ->and(createKeyResolver(ipv6Prefix: 128)->resolve($request))->toBe('2001:db8:aa:bbcc::1/128');
    });

    it('keys an IPv4-mapped IPv6 client by its IPv4 address', function (): void {
        $request = new Request(server: ['REMOTE_ADDR' => '::ffff:203.0.113.9']);

        expect(createKeyResolver()->resolve($request))->toBe('203.0.113.9');
    });

    it('keys IPv4 clients by their full address', function (): void {
        $resolver = createKeyResolver();

        expect($resolver->resolve(new Request(server: ['REMOTE_ADDR' => '203.0.113.9'])))->toBe('203.0.113.9')
            ->and($resolver->resolve(new Request(server: ['REMOTE_ADDR' => '203.0.113.10'])))->toBe('203.0.113.10');
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
