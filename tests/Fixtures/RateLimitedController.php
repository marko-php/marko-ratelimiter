<?php

declare(strict_types=1);

namespace Marko\RateLimiter\Tests\Fixtures;

use Marko\RateLimiter\Attributes\RateLimit;

#[RateLimit(maxAttempts: 10, decaySeconds: 120)]
class RateLimitedController
{
    public function index(): void {}

    #[RateLimit(maxAttempts: 2, decaySeconds: 30)]
    public function login(): void {}

    #[RateLimit(maxAttempts: 2, name: 'auth')]
    public function register(): void {}

    #[RateLimit(maxAttempts: 2, name: 'auth')]
    public function passwordReset(): void {}
}
