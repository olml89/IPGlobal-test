<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain\Address\ZipCode;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;

final class ZipCode extends StringValueObject
{
    public function __construct(string $zipCode, ZipCodeValidator $validator)
    {
        $this->ensureIsAValidZipCode($zipCode, $validator);

        parent::__construct($zipCode);
    }

    private function ensureIsAValidZipCode(string $zipCode, ZipCodeValidator $validator): void
    {
        if (!$validator->isValid($zipCode)) {
            throw new InvalidZipCodeException($zipCode);
        }
    }
}
