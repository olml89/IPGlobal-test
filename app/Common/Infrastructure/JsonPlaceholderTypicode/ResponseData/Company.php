<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData;

final class Company
{
    public function __construct(
        public readonly string $name,
        public readonly string $catchPhrase,
        public readonly string $bs,
    ) {}
}
