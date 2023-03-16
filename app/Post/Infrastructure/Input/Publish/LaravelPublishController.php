<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Infrastructure\Input\Publish;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\UrlGenerator;
use olml89\IPGlobalTest\Common\Infrastructure\Laravel\Http\Controllers\Controller;
use olml89\IPGlobalTest\Post\Application\Publish\PublishUseCase as PublishPost;
use Symfony\Component\HttpFoundation\Response;

class LaravelPublishController extends Controller
{
    public function __construct(
        private readonly PublishPost $publishPost,
        private readonly UrlGenerator $urlGenerator,
    ) {}

    public function __invoke(LaravelPublishPostRequest $request): JsonResponse
    {
        $result = $this->publishPost->publish($request->validated());

        return new JsonResponse(
            data: $result,
            status: Response::HTTP_CREATED,
            headers: [
                'Location' => $this->urlGenerator->current().'/'.$result->id,
            ],
        );
    }
}
