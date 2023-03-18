<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Domain;

use DateTimeImmutable;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\IPGlobalTest\User\Domain\User;

final class Post
{
    private readonly DateTimeImmutable $postedAt;

    public function __construct(
        private readonly Uuid $id,
        private readonly User $user,
        private readonly StringValueObject $title,
        private readonly StringValueObject $body,
    ) {
        // We could handle this with Doctrine hooks but is generally preferred to keep separated the Domain
        // model from the current persistence implementation, a cleaner way to do it would be to update the field
        // through an event
        $this->postedAt = new DateTimeImmutable();
    }

    public function id(): Uuid
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

    public function postedAt(): DateTimeImmutable
    {
        return $this->postedAt;
    }
}
