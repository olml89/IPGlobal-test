<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Infrastructure\Hashing;

use Illuminate\Contracts\Hashing\Hasher as LaravelHashingContract;
use olml89\IPGlobalTest\User\Domain\Password\Hasher as HasherContract;

final class Hasher implements HasherContract
{
    public function __construct(
        private readonly LaravelHashingContract $laravelHasher,
    ) {}

    public function hash(string $password): string
    {
        return $this->laravelHasher->make($password);
    }

    public function check(string $password, string $hash): bool
    {
        return $this->laravelHasher->check($password, $hash);
    }
}
