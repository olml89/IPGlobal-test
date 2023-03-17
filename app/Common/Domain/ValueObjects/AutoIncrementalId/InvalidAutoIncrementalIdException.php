<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidAutoIncrementalIdException extends ValueObjectException
{
    public static function notBiggerThan0(int $id): self
    {
        return new self(
            sprintf('Auto-incremental IDs must be bigger than 0, <%s> provided', $id)
        );
    }
}
