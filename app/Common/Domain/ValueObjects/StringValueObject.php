<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects;

use JsonSerializable;
use Stringable;

class StringValueObject implements Stringable, JsonSerializable
{
    public function __construct(
        private readonly string $value,
    ) {}

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return (string)$this;
    }
}
