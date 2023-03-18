<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use ReflectionClass;
use ReflectionException;

abstract class ValidatedStringValueObjectType extends StringValueObjectType
{
    /** @var null|class-string<StringValueObject */
    protected ?string $childClass = null;

    abstract protected function getChildClassName(): string;

    private function ensureChildClassHasBeenSet(): void
    {
        if (is_null($this->childClass)) {
            $this->childClass = $this->getChildClassName();
        }
    }

    abstract protected function getChildTypeName(): string;

    public function getName(): string
    {
        return $this->getChildTypeName();
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        $this->ensureChildClassHasBeenSet();

        if (!($value instanceof $this->childClass)) {
            throw ConversionException::conversionFailed($value, $this->childClass);
        }

        return (string)$value;
    }

    /**
     * @throws ConversionException | ReflectionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): StringValueObject
    {
        $this->ensureChildClassHasBeenSet();

        if (!is_string($value)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        $reflectionClass = new ReflectionClass($this->childClass);
        $valueObject = $reflectionClass->newInstanceWithoutConstructor();
        $valueReflectionProperty = $reflectionClass->getParentClass()->getProperty('value');
        $valueReflectionProperty->setAccessible(true);
        $valueReflectionProperty->setValue($valueObject, $value);

        return $valueObject;
    }
}
