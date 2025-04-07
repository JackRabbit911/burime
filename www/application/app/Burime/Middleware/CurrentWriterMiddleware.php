<?php declare(strict_types=1);

namespace App\Burime\Middleware;

use App\Burime\Model\ModelPost;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Attribute;

#[Attribute]
class CurrentWriterMiddleware implements MiddlewareInterface
{
    public function __construct(private ModelPost $model) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $branch = $request->getAttribute('branch');

        if (!isset($branch->info['current_writer'])) {
            $current_writer = $this->model->findOwnerAuthorPostByWeight($branch->id, $branch->maxWeight) ?? 0;
            $branch->info['current_writer'] = $current_writer;
        }

        return $handler->handle($request);
    }
}
