<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain;

use olml89\IPGlobalTest\User\Domain\Email\Email;

interface UserRepository
{
    public function getByEmail(Email $email): ?User;

    /**
     * @throws UserStorageException
     */
    public function save(User $user): void;
}
