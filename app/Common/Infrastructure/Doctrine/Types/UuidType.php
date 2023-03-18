<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;
use ReflectionClass;
use ReflectionException;

final class UuidType extends GuidType
{
    private const NAME = 'uuid';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof Uuid)) {
            throw ConversionException::conversionFailed($value, Uuid::class);
        }

        return (string)$value;
    }

    /**
     * @throws ConversionException | ReflectionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Uuid
    {
        if (!is_string($value)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        $reflectionClass = new ReflectionClass(Uuid::class);
        $uuid = $reflectionClass->newInstanceWithoutConstructor();
        $valueReflectionProperty = $reflectionClass->getParentClass()->getProperty('value');
        $valueReflectionProperty->setAccessible(true);
        $valueReflectionProperty->setValue($uuid, $value);

        return $uuid;
    }
}
