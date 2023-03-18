<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Security\Application;

use olml89\IPGlobalTest\Common\Domain\RandomStringGenerator;
use olml89\IPGlobalTest\Security\Domain\Md5Hash\Md5Hash;
use olml89\IPGlobalTest\Security\Domain\Token;
use olml89\IPGlobalTest\Security\Domain\TokenRepository;
use olml89\IPGlobalTest\Security\Domain\TokenStorageException;
use olml89\IPGlobalTest\User\Domain\Email\Email;
use olml89\IPGlobalTest\User\Domain\Email\EmailValidator;
use olml89\IPGlobalTest\User\Domain\Email\InvalidEmailException;
use olml89\IPGlobalTest\User\Domain\Password\Hasher;
use olml89\IPGlobalTest\User\Domain\UserFinder;
use olml89\IPGlobalTest\User\Domain\UserNotFoundException;

final class AuthorizeUseCase
{
    public function __construct(
        private readonly EmailValidator $emailValidator,
        private readonly UserFinder $userFinder,
        private readonly Hasher $hasher,
        private readonly TokenRepository $tokenRepository,
        private readonly RandomStringGenerator $randomStringGenerator,
    ) {}

    /**
     * @throws InvalidEmailException | UserNotFoundException | TokenStorageException
     */
    public function authorize(AuthorizeData $authorizeData): AuthorizationResult
    {
        $email = new Email($authorizeData->email, $this->emailValidator);
        $user = $this->userFinder->findByEmail($email);

        if (!$user->password()->check($authorizeData->password, $this->hasher)) {
            throw UserNotFoundException::invalidPassword();
        }

        $token = $this->tokenRepository->getByUser($user);

        if (is_null($token) || $token->isExpired()) {
            $token = new Token(
                user: $user,
                hash: new Md5Hash($this->randomStringGenerator->generate())
            );

            $this->tokenRepository->save($token);
        }

        return new AuthorizationResult($token);
    }
}
