<?php declare(strict_types=1);

namespace App\Author\Controller;

use App\Author\Model\ModelAuthor;
use App\Author\Middleware\OwnerGuard;
use App\Author\Middleware\AuthorValidation;
use App\Author\Repo\Avatar;
use App\Author\Component\AuthorForm;
use App\Author\Author;
use App\Author\Model\ModelUserGroup;

use Az\Route\Route;
use Sys\Controller\WebController;

class Form extends WebController
{
    private ModelAuthor $model;

    public function __construct(ModelAuthor $model)
    {
        $this->model = $model;
    }

    #[OwnerGuard]
    public function __invoke(ModelUserGroup $modelUserGroup, $id = null)
    {
        $data['author'] = $this->request->getAttribute('author');
        return new AuthorForm($data['author'], $this->user->ownAuthors);
    }

    #[Route(methods: 'post')]
    #[AuthorValidation]
    public function save(Avatar $avatar, $id = null)
    {
        $data = $this->request->getParsedBody();

        if ($id) {
            $author = $this->model->find($id);
        } else {
            $id = $this->model->nextAI();
            $author = new Author;
            $author->id = $id;
            $author->owner = $this->user->id;
        }

        $author->update($data)->save();
        $avatar->save($this->request->getUploadedFiles()['avatar'], $author->id);

        return $this->redirect(path('author', ['id' => $id]));
    }
}
