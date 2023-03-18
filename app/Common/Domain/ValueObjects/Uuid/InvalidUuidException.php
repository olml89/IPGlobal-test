<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidUuidException extends ValueObjectException
{
    public function __construct(string $uuid)
    {
        parent::__construct(
            sprintf('Value must be a valid UUID, \'%s\' provided', $uuid)
        );
    }
}
