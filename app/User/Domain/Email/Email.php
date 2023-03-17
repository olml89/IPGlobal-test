<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain\Email;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;

final class Email extends StringValueObject
{
    public function __construct(string $email, EmailValidator $validator)
    {
        $this->ensureIsAValidEmail($email, $validator);

        parent::__construct($email);
    }

    private function ensureIsAValidEmail(string $email, EmailValidator $validator): void
    {
        if (!$validator->isValid($email)) {
            throw new InvalidEmailException($email);
        }
    }
}
