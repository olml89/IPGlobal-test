<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Application;

use olml89\IPGlobalTest\Common\Domain\JsonSerializableObject;
use olml89\IPGlobalTest\Post\Domain\Post;
use olml89\IPGlobalTest\User\Application\UserResult;

final class PostResult extends JsonSerializableObject
{
    public readonly string $id;
    public readonly UserResult $user;
    public readonly string $title;
    public readonly string $body;
    public readonly string $posted_at;

    public function __construct(Post $post)
    {
        $this->id = (string)$post->id();
        $this->user = new UserResult($post->user());
        $this->title = (string)$post->title();
        $this->body = (string)$post->body();
        $this->posted_at = $post->postedAt()->format('c');
    }
}
