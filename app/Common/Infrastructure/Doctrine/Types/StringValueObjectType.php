<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;

class StringValueObjectType extends StringType
{
    private const NAME = 'StringValueObject';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof StringValueObject)) {
            throw ConversionException::conversionFailed($value, StringValueObject::class);
        }

        return (string)$value;
    }

    /**
     * @throws ConversionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): StringValueObject
    {
        if (!is_string($value)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return new StringValueObject($value);
    }
}
