<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Security\Domain;

use DateTimeImmutable;
use olml89\IPGlobalTest\Security\Domain\Md5Hash\Md5Hash;
use olml89\IPGlobalTest\User\Domain\User;

class Token
{
    // The id can't be set as readonly here because of Doctrine.
    // This is a bit of an infrastructure permeating into our domain code smell, but it's a good trade in this case.
    private int $id;
    private readonly DateTimeImmutable $expiresAt;

    public function __construct(
        private readonly User $user,
        private readonly Md5Hash $hash,
    ) {
        $this->expiresAt = (new DateTimeImmutable())->modify('+1 hour');
    }

    public function user(): User
    {
        return $this->user;
    }

    public function hash(): Md5Hash
    {
        return $this->hash;
    }

    public function expiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        $currentDatetime = new DateTimeImmutable();

        return $currentDatetime >= $this->expiresAt;
    }
}
