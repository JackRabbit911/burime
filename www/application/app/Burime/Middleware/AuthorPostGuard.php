<?php declare(strict_types=1);

namespace App\Burime\Middleware;

use App\Burime\Model\ModelPost;
use App\Burime\Service\PostPermissions;
use Az\Route\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Attribute;

#[Attribute]
final class AuthorPostGuard implements MiddlewareInterface
{
    private ModelPost $modelPost;

    public function __construct(ModelPost $modelPost)
    {
        $this->modelPost = $modelPost;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $branch = $request->getAttribute('branch');

        $redirect = path('branch', ['branch_id' => $branch->id]);

        if (!$user = $request->getAttribute('user')) {
            redirect($redirect, 302);
        }

        $branch->maxWeight = $this->modelPost->getMaxWeight($branch->id);
        $post_last = $this->modelPost->getLast($branch->id);

        $params = $request->getAttribute(Route::class)->getParameters();

        $postPermissions = new PostPermissions($branch, $user);


        if (isset($params['post_id'])) {
            $post_current = $this->modelPost->findPost($params['post_id'], $branch->id);

            if (!$postPermissions->edit($post_current)) {
                redirect($redirect, 302);
            }
        } else {
            if (!$postPermissions->write($post_last)) {
                redirect($redirect, 302);
            }
        }

        $request = $request->withAttribute('branch', $branch)
            ->withAttribute('last', $post_last);

        if (isset($post_current)) {
            $request = $request->withAttribute('current', $post_current);
        }

        return $handler->handle($request);
    }
}
