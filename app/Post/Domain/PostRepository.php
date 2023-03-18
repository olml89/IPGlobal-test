<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Domain;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;

interface PostRepository
{
    public function get(Uuid $id): ?Post;

    /**
     * @throws PostStorageException
     */
    public function save(Post $post): void;
}
