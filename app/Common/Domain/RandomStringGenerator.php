<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain;

interface RandomStringGenerator
{
    public function generate(int $length = null): string;
}
