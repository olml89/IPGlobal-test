<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects;

class IntValueObject
{
    public function __construct(
        private readonly int $value,
    ) {}

    public function toInt(): int
    {
        return $this->value;
    }
}
