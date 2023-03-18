<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Security\Application;

final class AuthenticationData
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}
