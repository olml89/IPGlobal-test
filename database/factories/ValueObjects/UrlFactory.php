<?php declare(strict_types=1);

namespace Database\Factories\ValueObjects;

use olml89\IPGlobalTest\User\Domain\Url\Url;
use ReflectionClass;
use ReflectionException;

final class UrlFactory extends StringValueObjectFactory
{
    /**
     * @throws ReflectionException
     */
    public function create(): Url
    {
        $url = (new ReflectionClass(Url::class))->newInstanceWithoutConstructor();
        $this->setValue($url, $this->faker->url());

        return $url;
    }
}
