<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Security\Domain;

use olml89\IPGlobalTest\Security\Domain\Md5Hash\Md5Hash;
use olml89\IPGlobalTest\User\Domain\User;

interface TokenRepository
{
    public function getByUser(User $user): ?Token;

    public function getByHash(Md5Hash $hash): ?Token;

    /**
     * @throws TokenStorageException
     */
    public function save(Token $token): void;
}
