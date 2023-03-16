<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\Validation;

interface Rule
{
    public function check(): bool;
    public function failureMessage(): string;
}
