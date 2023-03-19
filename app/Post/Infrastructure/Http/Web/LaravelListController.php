<?php declare(strict_types=1);

namespace olml89\IPGlobalTest\Post\Infrastructure\Http\Web;

use Illuminate\View\View;
use olml89\IPGlobalTest\Post\Application\List\ListUseCase;

final class LaravelListController
{
    public function __construct(
        private readonly ListUseCase $listPosts,
    ) {}

    public function __invoke(): View
    {
        return view(
            view: 'posts',
            data: ['posts' => $this->listPosts->all()]
        );
    }
}
