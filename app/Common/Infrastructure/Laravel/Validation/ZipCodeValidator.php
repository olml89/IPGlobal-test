<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Validation;

use olml89\IPGlobalTest\User\Domain\Address\ZipCode\ZipCodeValidator as ZipCodeValidatorContract;

final class ZipCodeValidator extends Validator implements ZipCodeValidatorContract
{
    public function isValid(string $zipCode): bool
    {
        // https://minuteoflaravel.com/validation/how-to-validate-zip-code-in-laravel/
        return $this->factory->make([$zipCode], ['postal_code:US'])->passes();
    }
}
