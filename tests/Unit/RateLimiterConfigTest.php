<?php

declare(strict_types=1);

use Marko\Config\Exceptions\ConfigNotFoundException;
use Marko\RateLimiter\Config\RateLimiterConfig;
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
            ->and($config['trusted_proxies'])->toBe([]);
    });
});
