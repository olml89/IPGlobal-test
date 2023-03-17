<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Validation;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\Url\UrlValidator as UrlValidatorContract;

final class UrlValidator extends Validator implements UrlValidatorContract
{
    public function isValid(string $url): bool
    {
        return $this->factory->make([$url], ['url'])->passes();
    }
}
