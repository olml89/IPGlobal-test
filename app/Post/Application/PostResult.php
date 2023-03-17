<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Application;

use olml89\IPGlobalTest\Common\Domain\JsonSerializableObject;
use olml89\IPGlobalTest\Post\Application\Retrieve\UserResult;
use olml89\IPGlobalTest\Post\Domain\Post;

final class PostResult extends JsonSerializableObject
{
    public readonly int $id;
    public readonly UserResult $user;
    public readonly string $title;
    public readonly string $body;

    public function __construct(Post $post)
    {
        $this->id = $post->id()->toInt();
        $this->user = new UserResult($post->user());
        $this->title = (string)$post->title();
        $this->body = (string)$post->body();
    }
}
