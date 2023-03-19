<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Application\List;

use olml89\IPGlobalTest\Post\Application\PostResult;
use olml89\IPGlobalTest\Post\Domain\Post;
use olml89\IPGlobalTest\Post\Domain\PostRepository;

final class ListUseCase
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {}

    /**
     * @return PostResult[]
     */
    public function all(): array
    {
        return array_map(
            fn(Post $post): PostResult => new PostResult($post),
            $this->postRepository->all(),
        );
    }
}
