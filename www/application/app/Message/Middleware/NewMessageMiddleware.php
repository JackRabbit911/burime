<?php declare(strict_types=1);

namespace App\Message;

use App\Message\Model\ModelStat;
use Sys\Template\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class NewMessageMiddleware implements MiddlewareInterface
{
    private ModelStat $model;

    public function __construct(ModelStat $model)
    {
        $this->model = $model;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = $request->getAttribute('user');

        if ($user) {
            $data['newmsg'] = $this->model->getNewMsgCount($user->id);
        }

        
        if(isset($data)) {
            $app = container()->get(App::class);
            $app->add('badges', $data);
        }

        return $handler->handle($request);
    }
}
