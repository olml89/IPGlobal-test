<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain;

interface UserRepository
{
    /**
     * @throws UserStorageException
     */
    public function save(User $user): void;
}
