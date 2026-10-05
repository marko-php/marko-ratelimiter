<?php

declare(strict_types=1);

namespace Marko\RateLimiter\Config;

use Marko\Config\ConfigRepositoryInterface;
use Marko\Config\Exceptions\ConfigNotFoundException;

readonly class RateLimiterConfig
{
    public function __construct(
        private ConfigRepositoryInterface $configRepository,
    ) {}

    /**
     * @throws ConfigNotFoundException
     */
    public function defaultMaxAttempts(): int
    {
        return $this->configRepository->getInt('ratelimiter.default_max_attempts');
    }

    /**
     * @throws ConfigNotFoundException
     */
    public function defaultDecaySeconds(): int
    {
        return $this->configRepository->getInt('ratelimiter.default_decay_seconds');
    }
}
