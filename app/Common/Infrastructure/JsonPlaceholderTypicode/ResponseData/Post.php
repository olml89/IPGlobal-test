<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\JsonPlaceholderTypicode\ResponseData;

use Psr\Http\Message\ResponseInterface;

final class Post
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly string $title,
        public readonly string $body,
    ) {}

    public static function fromHttpResponse(ResponseInterface $response): self
    {
        $data = json_decode($response->getBody()->getContents(), true);

        return new self(
            id: $data['id'],
            userId: $data['userId'],
            title: $data['title'],
            body: $data['body'],
        );
    }
}
