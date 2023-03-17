<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain\Address\ZipCode;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidZipCodeException extends ValueObjectException
{
    public function __construct(string $zipCode)
    {
        parent::__construct(
            sprintf('Value must be a valid ZIP code, \'%s\' provided', $zipCode)
        );
    }
}
