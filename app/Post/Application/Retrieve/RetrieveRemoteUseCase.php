<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Application\Retrieve;

use olml89\IPGlobalTest\Post\Application\PostResult;
use olml89\IPGlobalTest\Post\Domain\PostNotFoundException;
use olml89\IPGlobalTest\Post\Infrastructure\Http\Api\Get\JsonTypicodePostGetter;

final class RetrieveRemoteUseCase
{
    public function __construct(
        private readonly JsonTypicodePostGetter $remotePostGetter,
    ) {}

    /**
     * @throws PostNotFoundException
     */
    public function retrieve(int $id): PostResult
    {
        $post = $this->remotePostGetter->get($id);

        return new PostResult($post);
    }
}
