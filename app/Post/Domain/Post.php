<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Domain;

use DateTimeImmutable;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\StringValueObject;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\Uuid;
use olml89\IPGlobalTest\User\Domain\User;

class Post
{
    private readonly DateTimeImmutable $postedAt;

    public function __construct(
        private readonly Uuid $id,
        private readonly User $user,
        private StringValueObject $title,
        private StringValueObject $body,
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

    public function setTitle(StringValueObject $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function body(): StringValueObject
    {
        return $this->body;
    }

    public function setBody(StringValueObject $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function postedAt(): DateTimeImmutable
    {
        return $this->postedAt;
    }
}
