<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Application\Create;

use Database\Factories\UserFactory;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;
use olml89\IPGlobalTest\User\Application\UserResult;
use olml89\IPGlobalTest\User\Domain\Email\Email;
use olml89\IPGlobalTest\User\Domain\Email\EmailValidator;
use olml89\IPGlobalTest\User\Domain\Password\Hasher;
use olml89\IPGlobalTest\User\Domain\Password\Password;
use olml89\IPGlobalTest\User\Domain\UserCreationException;
use olml89\IPGlobalTest\User\Domain\UserStorageException;
use olml89\IPGlobalTest\User\Domain\UserRepository;
use ReflectionException;

final class CreateRandomUseCase
{
    public function __construct(
        private readonly EmailValidator $emailValidator,
        private readonly Hasher $hasher,
        private readonly UserFactory $userFactory,
        private readonly UserRepository $userRepository,
    ) {}

    /**
     * @throws UserCreationException | UserStorageException
     */
    public function create(string $email, string $password): UserResult
    {
        try {
            $user = $this->userFactory->random()
                ->setEmail(new Email($email, $this->emailValidator))
                ->setPassword(Password::create($password, $this->hasher));

            $this->userRepository->save($user);

            return new UserResult($user);
        }
        catch (ReflectionException|ValueObjectException $e) {
            throw new UserCreationException($e->getMessage(), $e);
        }
    }
}
