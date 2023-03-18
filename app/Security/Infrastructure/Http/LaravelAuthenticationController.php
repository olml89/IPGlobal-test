<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Security\Infrastructure\Http;

use Illuminate\Http\JsonResponse;
use olml89\IPGlobalTest\Security\Application\AuthenticateUseCase;
use olml89\IPGlobalTest\Security\Domain\TokenStorageException;
use olml89\IPGlobalTest\User\Domain\Email\InvalidEmailException;
use olml89\IPGlobalTest\User\Domain\UserNotFoundException;

final class LaravelAuthenticationController
{
    public function __construct(
        private readonly AuthenticateUseCase $authenticateUseCase,
    ) {}

    /**
     * @throws InvalidEmailException | UserNotFoundException | TokenStorageException
     */
    public function __invoke(LaravelAuthenticationRequest $request): JsonResponse
    {
        $result = $this->authenticateUseCase->authenticate($request->validated());

        return new JsonResponse(
            data: $result,
            status: 200,
        );
    }
}
