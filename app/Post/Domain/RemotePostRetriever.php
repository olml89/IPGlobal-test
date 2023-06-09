<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Domain;

interface RemotePostRetriever
{
    /**
     * @throws PostNotFoundException
     */
    public function get(int $id): Post;
}
