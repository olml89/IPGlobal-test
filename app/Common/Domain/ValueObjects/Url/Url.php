<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\ValueObjects\Url;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;

final class Url extends StringValueObject
{
    public function __construct(string $url, UrlValidator $validator)
    {
        $this->ensureIsAValidUrl($url, $validator);

        parent::__construct($url);
    }

    private function ensureIsAValidUrl(string $url, UrlValidator $validator): void
    {
        if (!$validator->isValid($url)) {
            throw new InvalidUrlException($url);
        }
    }
}
