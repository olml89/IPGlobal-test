<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain\Password;

interface Hasher
{
    public function hash(string $password): string;
    public function check(string $password, string $hash): bool;
}
