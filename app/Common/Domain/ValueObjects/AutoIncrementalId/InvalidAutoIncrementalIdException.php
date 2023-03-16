<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidAutoIncrementalIdException extends ValueObjectException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
