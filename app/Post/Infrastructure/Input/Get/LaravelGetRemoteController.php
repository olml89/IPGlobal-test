<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Infrastructure\Input\Get;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use olml89\IPGlobalTest\Post\Application\Retrieve\RetrieveRemoteUseCase as RetrievePost;
use Symfony\Component\HttpFoundation\Response;

final class LaravelGetRemoteController
{
    public function __construct(
        private readonly RetrievePost $retrievePost,
    ) {}
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $result = $this->retrievePost->retrieve($id);

        return new JsonResponse(
            data: $result,
            status: Response::HTTP_OK,
        );
    }
}
