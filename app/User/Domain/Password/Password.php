<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain\Password;

final class Password
{
    private function __construct(
        private readonly string $hash,
    ) {}

    public static function create(string $password, Hasher $hasher): self
    {
        return new self($hasher->hash($password));
    }

    public function check(string $password, Hasher $hasher): bool
    {
        return $hasher->check($password, $this->hash);
    }
}
