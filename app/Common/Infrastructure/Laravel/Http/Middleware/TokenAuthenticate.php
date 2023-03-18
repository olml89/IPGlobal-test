<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Common\Infrastructure\Laravel\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use olml89\IPGlobalTest\Security\Domain\Md5Hash\Md5Hash;
use olml89\IPGlobalTest\Security\Domain\TokenRepository;
use olml89\IPGlobalTest\User\Domain\User;

final class TokenAuthenticate
{
    public function __construct(
        private readonly TokenRepository $tokenRepository,
    ) {}

    /**
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next, string ...$guards): JsonResponse
    {
        $tokenHash = $request->header('Api-Token');

        if (is_null($tokenHash)) {
            throw new AuthenticationException('Api-Token header not set');
        }

        $token = $this->tokenRepository->getByHash(Md5Hash::fromHash($tokenHash));

        if (is_null($token) || $token->isExpired()) {
            throw new AuthenticationException('Api token not set or expired');
        }

        $request->setUserResolver(fn(): User => $token->user());

        return $next($request);
    }
}
