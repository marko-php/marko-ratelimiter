<?php

declare(strict_types=1);

use Marko\Config\Exceptions\ConfigNotFoundException;
use Marko\RateLimiter\Config\RateLimiterConfig;
use Marko\RateLimiter\Exceptions\ClientIpException;
use Marko\Testing\Fake\FakeConfigRepository;

describe('RateLimiterConfig', function (): void {
    it('reads default limits from config', function (): void {
        $config = new RateLimiterConfig(new FakeConfigRepository([
            'ratelimiter.default_max_attempts' => 120,
            'ratelimiter.default_decay_seconds' => 30,
        ]));

        expect($config->defaultMaxAttempts())->toBe(120)
            ->and($config->defaultDecaySeconds())->toBe(30);
    });

    it('throws when a default limit is missing from config', function (): void {
        $config = new RateLimiterConfig(new FakeConfigRepository([]));

        expect(fn (): int => $config->defaultMaxAttempts())
            ->toThrow(ConfigNotFoundException::class);
    });

    it('ships default limits in the config file', function (): void {
        $config = require dirname(__DIR__, 2) . '/config/ratelimiter.php';

        expect($config['default_max_attempts'])->toBe(60)
            ->and($config['default_decay_seconds'])->toBe(60)
            ->and($config['trusted_proxies'])->toBe([])
            ->and($config['ipv6_prefix'])->toBe(64);
    });

    it('reads the ipv6 prefix from config', function (): void {
        $config = new RateLimiterConfig(new FakeConfigRepository([
            'ratelimiter.ipv6_prefix' => 56,
        ]));

        expect($config->ipv6Prefix())->toBe(56);
    });

    it('throws when the ipv6 prefix is outside 1-128', function (int $prefix): void {
        $config = new RateLimiterConfig(new FakeConfigRepository([
            'ratelimiter.ipv6_prefix' => $prefix,
        ]));

        expect(fn (): int => $config->ipv6Prefix())
            ->toThrow(ClientIpException::class, "ratelimiter.ipv6_prefix must be between 1 and 128, got $prefix");
    })->with([0, -1, 129]);
});
