<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects\Email;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidEmailException extends ValueObjectException
{
    public function __construct(string $email)
    {
        parent::__construct(
            sprintf('Value must be a valid email, \'%s\' provided', $email)
        );
    }
}
