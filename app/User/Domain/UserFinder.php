<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain;

use olml89\IPGlobalTest\User\Domain\Email\Email;

final class UserFinder
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    /**
     * @throws UserNotFoundException
     */
    public function findByEmail(Email $email): User
    {
        return $this->userRepository->getByEmail($email)
            ?? throw UserNotFoundException::withEmail($email);
    }
}
