<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Validation;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\Email\EmailValidator as EmailValidatorContract;

final class EmailValidator extends Validator implements EmailValidatorContract
{
    public function isValid(string $email): bool
    {
        return $this->factory->make([$email], ['email'])->passes();
    }
}
