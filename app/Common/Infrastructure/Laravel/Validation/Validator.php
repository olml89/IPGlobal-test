<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Validation;

use Illuminate\Validation\Factory as ValidatorFactory;

abstract class Validator
{
    public function __construct(
        protected readonly ValidatorFactory $factory,
    ) { }
}
