<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain\Address\Geolocation;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\ValueObjectException;

final class InvalidGeolocationException extends ValueObjectException
{
    private function __construct(string $parameter, int $maxValue, float $value)
    {
        parent::__construct(
            sprintf(
                '%s must be in the range [%s, %s], <%s> provided',
                $parameter,
                -$maxValue,
                $maxValue,
                $value,
            )
        );
    }

    public static function latitude(float $latitude, float $maxValue): self
    {
        return new self('Latitude', (int)$maxValue, $latitude);
    }

    public static function longitude(float $latitude, float $maxValue): self
    {
        return new self('Longitude', (int)$maxValue, $latitude);
    }
}
