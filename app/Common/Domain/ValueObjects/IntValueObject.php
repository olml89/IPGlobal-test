<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects;

use JsonSerializable;

class IntValueObject implements JsonSerializable
{
    public function __construct(
        private readonly int $value,
    ) {}

    public function toInt(): int
    {
        return $this->value;
    }

    public function jsonSerialize(): int
    {
        return $this->toInt();
    }
}
