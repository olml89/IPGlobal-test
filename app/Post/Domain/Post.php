<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Domain;

use olml89\IPGlobalTest\Common\Domain\ValueObjects\AutoIncrementalId\AutoIncrementalId;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\User\Domain\User;

final class Post
{
    public function __construct(
        private readonly AutoIncrementalId $id,
        private readonly User $user,
        private readonly StringValueObject $title,
        private readonly StringValueObject $body,
    ) {}

    public function id(): AutoIncrementalId
    {
        return $this->id;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function title(): StringValueObject
    {
        return $this->title;
    }

    public function body(): StringValueObject
    {
        return $this->body;
    }
}
