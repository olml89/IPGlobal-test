<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\IntegerType;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId\AutoIncrementalId;

final class AutoIncrementalIdType extends IntegerType
{
    private const NAME = 'AutoIncrementalId';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): int
    {
        if (!($value instanceof AutoIncrementalId)) {
            throw ConversionException::conversionFailed($value, AutoIncrementalId::class);
        }

        return $value->toInt();
    }

    /**
     * @throws ConversionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): AutoIncrementalId
    {
        if ($value instanceof AutoIncrementalId) {
            return $value;
        }

        if (!is_int($value)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return new AutoIncrementalId($value);
    }
}
