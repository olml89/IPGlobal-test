<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects;

use olml89\IPGlobalTest\Common\Domain\Exceptions\DomainException;

abstract class ValueObjectException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
