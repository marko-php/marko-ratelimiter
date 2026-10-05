<?php

declare(strict_types=1);

use Marko\RateLimiter\Attributes\RateLimit;
use Marko\RateLimiter\Exceptions\RateLimitException;

describe('RateLimit attribute', function (): void {
    it('targets classes and methods', function (): void {
        $attribute = new ReflectionClass(RateLimit::class)->getAttributes(Attribute::class)[0]->newInstance();

        expect($attribute->flags)->toBe(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD);
    });

    it('exposes the declared limits and bucket name', function (): void {
        $rateLimit = new RateLimit(maxAttempts: 5, decaySeconds: 30, name: 'login');

        expect($rateLimit->maxAttempts)->toBe(5)
            ->and($rateLimit->decaySeconds)->toBe(30)
            ->and($rateLimit->name)->toBe('login');
    });

    it('leaves unset limits null so config defaults apply', function (): void {
        $rateLimit = new RateLimit();

        expect($rateLimit->maxAttempts)->toBeNull()
            ->and($rateLimit->decaySeconds)->toBeNull()
            ->and($rateLimit->name)->toBeNull();
    });

    it('rejects a non-positive maxAttempts or decaySeconds', function (): void {
        expect(fn (): RateLimit => new RateLimit(maxAttempts: 0))
            ->toThrow(RateLimitException::class, 'maxAttempts')
            ->and(fn (): RateLimit => new RateLimit(decaySeconds: -1))
            ->toThrow(RateLimitException::class, 'decaySeconds');
    });

    it('rejects an empty bucket name', function (): void {
        expect(fn (): RateLimit => new RateLimit(name: ''))
            ->toThrow(RateLimitException::class, 'name');
    });
});
