<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Domain;

interface PostRepository
{
    /**
     * @throws PostStorageException
     */
    public function save(Post $post): void;
}
