<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Security\Infrastructure\Input;

use Illuminate\Http\JsonResponse;
use olml89\IPGlobalTest\Security\Application\AuthenticateUseCase;

final class LaravelAuthenticationController
{
    public function __construct(
        private readonly AuthenticateUseCase $authorizeUseCase,
    ) {}

    public function __invoke(LaravelAuthenticationRequest $request): JsonResponse
    {
        $result = $this->authorizeUseCase->authorize($request->validated());

        return new JsonResponse(
            data: $result,
            status: 200,
        );
    }
}
