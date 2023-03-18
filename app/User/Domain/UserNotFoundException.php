<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain;

use olml89\IPGlobalTest\Common\Domain\Exceptions\NotFoundException;
use olml89\IPGlobalTest\User\Domain\Email\Email;

final class UserNotFoundException extends NotFoundException
{
    public static function withEmail(Email $email): self
    {
        return new self(
            sprintf('User with email \'%s\' does not exist', $email)
        );
    }

    public static function invalidPassword(): self
    {
        return new self(
            sprintf('The provided password is invalid')
        );
    }
}
