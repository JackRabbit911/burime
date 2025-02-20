<?php declare(strict_types=1);

namespace App\Private;

use App\Private\ModelPrivate;
use Common\Enum\MemberRole;
use Common\Contract\BranchInterface;
use Auth\Middleware\AuthGuardMiddleware;
use Sys\Controller\WebController;
use Psr\Container\ContainerInterface;

#[AuthGuardMiddleware]
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
        $favorites = $this->model->getUserGroupMembers($this->user->id, MemberRole::Favorive);
        $friends = $this->model->getUserGroupMembers($this->user->id, MemberRole::Friend);

        $data = [
            'authors' => $authors,
            'favorites' => $favorites,
            'friends' => $friends,
        ];

        return view('private/authors', $data);
    }

    public function books(ContainerInterface $container)
    {
        $this->app->add('coverpath', trim($container->get(BranchInterface::class)::COVERPATH, '.'));

        $my_books = $this->model->getMyBooks($this->user->id);

        // $my_books = $modelBranch->getByUser($this->user->id);

        // $my_books = $modelBranch->getByOwner($this->user->id);
        // $authors = $modelAuthor->getUsersGroups($this->user->id);
        // $favorites = $modelUserGroup->getUserGroupMembers($this->user->id, Role::FAVORITE);
        // $friends = $modelUserGroup->getUserGroupMembers($this->user->id, Role::FRIEND);

        $data = [
            'my_books' => $my_books,
            // 'favorites' => $favorites,
            // 'friends' => $friends,
        ];

        return view('web/personal/books', $data);
    }
}
