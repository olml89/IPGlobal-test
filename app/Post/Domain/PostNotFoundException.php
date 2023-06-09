<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Domain;

use olml89\IPGlobalTest\Common\Domain\Exceptions\NotFoundException;
use Throwable;

final class PostNotFoundException extends NotFoundException
{
    public function __construct(string $id, ?Throwable $e = null)
    {
        parent::__construct(
            message: sprintf('Post <%s> not found', $id),
            infrastructureException: $e,
        );
    }
}
