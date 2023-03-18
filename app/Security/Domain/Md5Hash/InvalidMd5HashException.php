<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Security\Domain\Md5Hash;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidMd5HashException extends ValueObjectException
{
    public function __construct(string $hash)
    {
        parent::__construct(
            sprintf('Value must be a valid md5 hash, \'%s\' provided', $hash)
        );
    }
}
