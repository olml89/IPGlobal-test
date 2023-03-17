<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData;

final class Geo
{
    public function __construct(
        public readonly float $lat,
        public readonly float $lng,
    ) {}
}
