<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Application;

use olml89\IPGlobalTest\Post\Domain\Post;

final class PublishUseCaseResult
{
    public readonly int $id;
    public readonly int $user_id;
    public readonly string $title;
    public readonly string $body;

    public function __construct(Post $post)
    {
        $this->id = $post->id()->toInt();
        $this->user_id = $post->userId()->toInt();
        $this->title = (string)$post->title();
        $this->body = (string)$post->body();
    }
}
