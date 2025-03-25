<?php declare(strict_types=1);

namespace App\Private;

use App\Private\ModelPrivate;
use Common\Enum\MemberRole;
use Common\Contract\BranchInterface;
use Common\Middleware\AuthGuard;
use Sys\Controller\WebController;
use Psr\Container\ContainerInterface;

#[AuthGuard]
final class PrivateController extends WebController
{
    private ModelPrivate $model;

    public function __construct(ModelPrivate $model)
    {
        $this->model = $model;
    }

    public function authors()
    {
        $authors = $this->model->getMyGroups($this->user->id);
        // $favorites = $this->model->getUserGroupMembers($this->user->id, MemberRole::Favorive);
        // $friends = $this->model->getUserGroupMembers($this->user->id, MemberRole::Friend);

        $data['authors'] = $authors;
        $data['view'] = __FUNCTION__;

        return view('private/my_wrap', $data);
    }

    public function books(ContainerInterface $container)
    {
        $this->app->add('coverpath', trim($container->get(BranchInterface::class)::COVERPATH, '.'));

        $data['my_books'] = $this->model->getMyBooks($this->user->id);
        $data['view'] = __FUNCTION__;

        return view('private/my_wrap', $data);
    }
}
