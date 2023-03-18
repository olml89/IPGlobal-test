<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Application\Retrieve;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\UuidValidator;
use olml89\IPGlobalTest\Post\Domain\PostNotFoundException;
use olml89\IPGlobalTest\Post\Application\PostResult;
use olml89\IPGlobalTest\Post\Domain\PostFinder;

final class RetrieveUseCase
{
    public function __construct(
        private readonly UuidValidator $uuidValidator,
        private readonly PostFinder $postFinder,
    ) {}

    /**
     * @throws InvalidUuidException | PostNotFoundException
     */
    public function retrieve(string $id): PostResult
    {
        $id = Uuid::create($id, $this->uuidValidator);
        $post = $this->postFinder->find($id);

        return new PostResult($post);
    }
}
