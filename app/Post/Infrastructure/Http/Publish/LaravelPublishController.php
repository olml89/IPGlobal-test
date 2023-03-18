<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Infrastructure\Http\Publish;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\UrlGenerator;
use olml89\IPGlobalTest\Common\Infrastructure\Laravel\Http\Controllers\Controller;
use olml89\IPGlobalTest\Post\Application\Publish\PublishUseCase as PublishPost;
use olml89\IPGlobalTest\Post\Domain\PostCreationException;
use olml89\IPGlobalTest\Post\Domain\PostStorageException;
use Symfony\Component\HttpFoundation\Response;

class LaravelPublishController extends Controller
{
    public function __construct(
        private readonly PublishPost $publishPost,
        private readonly UrlGenerator $urlGenerator,
    ) {}

    /**
     * @throws AuthenticationException | PostCreationException | PostStorageException
     */
    public function __invoke(LaravelPublishRequest $request): JsonResponse
    {
        $result = $this->publishPost->publish(
            publishData: $request->validated(),
            user: $request->getAuthenticatedUser(),
        );

        return new JsonResponse(
            data: $result,
            status: Response::HTTP_CREATED,
            headers: [
                'Location' => $this->urlGenerator->current().'/'.$result->id,
            ],
        );
    }
}
