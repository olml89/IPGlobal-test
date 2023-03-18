<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Security\Application;

use olml89\IPGlobalTest\Common\Domain\JsonSerializableObject;
use olml89\IPGlobalTest\Security\Domain\Token;

final class AuthorizationResult extends JsonSerializableObject
{
    public readonly string $user_id;
    public readonly string $token;
    public readonly string $expires_at;

    public function __construct(Token $token)
    {
        $this->user_id = (string)$token->user()->id();
        $this->token = (string)$token->hash();
        $this->expires_at = $token->expiresAt()->format('c');
    }
}
