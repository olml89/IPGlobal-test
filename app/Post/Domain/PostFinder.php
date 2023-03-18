<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Domain;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;

final class PostFinder
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {}

    /**
     * @throws PostNotFoundException
     */
    public function find(Uuid $uuid): Post
    {
        return $this->postRepository->get($uuid) ?? throw new PostNotFoundException((string)$uuid);
    }
}
