<?php declare(strict_types=1);

namespace App\Author\Controller;

use App\Author\Middleware\MembersValidation;
use App\Author\Model\ModelUserGroup;
use Sys\Controller\WebController;
use Az\Route\Route;

class Controls extends WebController
{
    #[Route(methods: 'post')]
    #[MembersValidation]
    public function __invoke(ModelUserGroup $model)
    {
        $data = $this->request->getParsedBody();

        switch ($data['action']) {
            case 'sendmsg':
                $query_string = http_build_query(['to' => $data['members']]);
                $query_string = empty($query_string) ? $query_string : '?' . $query_string;
                $uri = '/my/message/form' . $query_string;
                break;
            case 'chat':
                $uri = '/my/message/form'; //path('message', ['action' => 'form']);
                break;
            case 'subscribe':
                $uri = path('author', ['id' => $data['members'][0]]);
                $model->addToUserGroup($this->user->id, $data['members'][0], 100);
                break;
            case 'unsubscribe':
                $uri = path('author', ['id' => $data['members'][0]]);
                $model->removeFromUserGroup($this->user->id, $data['members'][0], 100);
                break;
        }

        return $this->redirect($uri);
    }
}
