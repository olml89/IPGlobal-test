<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use olml89\IPGlobalTest\User\Domain\Password\Password;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;

final class PasswordType extends StringType
{
    private const NAME = 'password';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws ConversionException | ReflectionException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        if (!($value instanceof Password)) {
            throw ConversionException::conversionFailed($value, Password::class);
        }

        $reflectionObject = new ReflectionObject($value);
        $hashReflectionProperty = $reflectionObject->getProperty('hash');
        $hashReflectionProperty->setAccessible(true);

        return $hashReflectionProperty->getValue($value);
    }

    /**
     * @throws ConversionException | ReflectionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): Password
    {
        if (!is_string($value)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        $reflectionClass = new ReflectionClass(Password::class);
        $password = $reflectionClass->newInstanceWithoutConstructor();
        $hashReflectionProperty = $reflectionClass->getProperty('hash');
        $hashReflectionProperty->setAccessible(true);
        $hashReflectionProperty->setValue($password, $value);

        return $password;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'CHAR(60)';
    }
}
