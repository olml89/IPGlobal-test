<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Domain\Exceptions;

use DomainException as PhpDomainException;
use Throwable;

abstract class DomainException extends PhpDomainException
{
    public function __construct(string $message, ?Throwable $infrastructureException)
    {
        parent::__construct(
            message: $message,
            previous: $infrastructureException,
        );
    }

    public function getInfrastructureException(): ?Throwable
    {
        return $this->getPrevious();
    }
}
