<?php declare(strict_types=1);

namespace Tests\Unit\Post\JsonPlaceholderTypicode;

use Faker\Generator as Faker;
use olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData\Post as PostData;
use Stringable;

final class PostDataGenerator implements Stringable
{
    private int $id;
    private int $userId;
    private string $title;
    private string $body;
    private PostData $postData;

    public function __construct(Faker $faker)
    {
        $this->id = $faker->randomNumber();
        $this->userId = $faker->randomNumber();
        $this->title = $faker->title();
        $this->body = $faker->text();

        $this->setPostData();
    }

    private function setPostData(): void
    {
        $this->postData = new PostData(
            $this->id,
            $this->userId,
            $this->title,
            $this->body,
        );
    }

    public function withId(int $id): self
    {
        $this->id = $id;
        $this->setPostData();

        return $this;
    }

    public function withUserId(int $userId): self
    {
        $this->userId = $userId;
        $this->setPostData();

        return $this;
    }

    public function withTitle(string $title): self
    {
        $this->title = $title;
        $this->setPostData();

        return $this;
    }

    public function __toString(): string
    {
        return json_encode($this->postData);
    }
}
