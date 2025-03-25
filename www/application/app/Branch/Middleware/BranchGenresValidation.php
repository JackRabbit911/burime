<?php declare(strict_types=1);

namespace App\Branch\Middleware;

use Attribute;
use Az\Validation\Validation;
use HttpSoft\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[Attribute]
class BranchGenresValidation implements MiddlewareInterface
{
    protected Validation $validation;

    public function __construct(Validation $validation)
    {
        $this->validation = $validation;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $request->getAttribute('session');
        $post = $request->getParsedBody();

        if (empty($post['genres'])) {
            $session->flash('validation_msg', __('Genre must be selected'));
            return new RedirectResponse($request->getServerParams()['HTTP_REFERER'], 302);
        }

        return $handler->handle($request);
    }
}
