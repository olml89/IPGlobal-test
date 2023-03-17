<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\User\Domain\Address\Geolocation;


use olml89\IPGlobalTest\Common\Domain\JsonSerializableObject;

final class Geolocation extends JsonSerializableObject
{
    public readonly float $latitude;
    public readonly float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->ensureValidLatitude($latitude);
        $this->ensureValidLongitude($longitude);

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    private function ensureValidLatitude(float $latitude): void
    {
        $maxValue = 90.0;

        if ($latitude < -$maxValue || $latitude > $maxValue) {
            throw InvalidGeolocationException::latitude($latitude, $maxValue);
        }
    }

    private function ensureValidLongitude(float $longitude): void
    {
        $maxValue = 180.0;

        if ($longitude < -$maxValue || $longitude > $maxValue) {
            throw InvalidGeolocationException::latitude($longitude, $maxValue);
        }
    }
}
