<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects\Email;

interface EmailValidator
{
    public function isValid(string $email): bool;
}
