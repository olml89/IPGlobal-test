<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain\Address\ZipCode;

interface ZipCodeValidator
{
    public function isValid(string $zipCode): bool;
}
