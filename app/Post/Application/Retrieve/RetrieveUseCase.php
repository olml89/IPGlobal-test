<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Application\Retrieve;

use olml89\IPGlobalTest\Post\Application\PostResult;
use olml89\IPGlobalTest\Post\Infrastructure\Input\Get\JsonTypicodePostGetter;

final class RetrieveUseCase
{
    public function __construct(
        private readonly JsonTypicodePostGetter $remotePostGetter,
    ) {}

    public function retrieve(int $id): PostResult
    {
        $post = $this->remotePostGetter->get($id);

        return new PostResult($post);
    }
}
