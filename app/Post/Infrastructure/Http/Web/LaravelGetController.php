<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Infrastructure\Http\Web;

use Illuminate\View\View;
use olml89\IPGlobalTest\Common\Domain\ValueObjects\Uuid\InvalidUuidException;
use olml89\IPGlobalTest\Post\Application\Retrieve\RetrieveUseCase;
use olml89\IPGlobalTest\Post\Domain\PostNotFoundException;

final class LaravelGetController
{
    public function __construct(
        private readonly RetrieveUseCase $retrievePost,
    ) {}

    /**
     * @throws InvalidUuidException | PostNotFoundException
     */
    public function __invoke(string $id): View
    {
        return view(
            view: 'post',
            data: ['post' => $this->retrievePost->retrieve($id)],
        );
    }
}
