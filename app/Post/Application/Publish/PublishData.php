<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Application\Publish;

final class PublishData
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $title,
        public readonly string $body,
    ) {}
}
