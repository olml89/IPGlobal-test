<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\Validation;

final class IntBiggerThan0 implements Rule
{
    public function __construct(
        private readonly int $value,
    ) {}

    public function check(): bool
    {
        return $this->value > 0;
    }

    public function failureMessage(): string
    {
        return sprintf('Value must be bigger than 0, <%s> provided', $this->value);
    }
}
