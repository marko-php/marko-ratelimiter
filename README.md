# marko/ratelimiter

Cache-backed rate limiter with route middleware --- per-route limits via `#[RateLimit]`, IPv6-safe keys and automatic `Retry-After` headers.

## Installation

```bash
composer require marko/ratelimiter
```

## Quick Example

```php
use Marko\RateLimiter\Attributes\RateLimit;
use Marko\RateLimiter\Middleware\RateLimitMiddleware;
use Marko\Routing\Attributes\Middleware;
use Marko\Routing\Attributes\Post;
use Marko\Routing\Http\Response;

class LoginController
{
    #[Post('/login')]
    #[Middleware(RateLimitMiddleware::class)]
    #[RateLimit(maxAttempts: 5, decaySeconds: 60, name: 'login')]
    public function login(): Response
    {
        return new Response('OK');
    }
}
```

## Documentation

Full configuration, middleware usage and API reference: [marko/ratelimiter](https://marko.build/docs/packages/ratelimiter/)
