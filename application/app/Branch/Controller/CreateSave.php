<?php declare(strict_types=1);

namespace App\Branch\Controller;

use App\Branch\Branch;
use App\Branch\Model\SaveCover;
use App\Branch\Model\AddAuthorsRepo;
use App\Branch\Service\SendInvitation;
use App\Branch\Middleware\BranchGenresValidation;
use App\Branch\Middleware\RulesValidation;
use App\Branch\Middleware\BranchAuthorsValidation;
use App\Branch\Middleware\BranchCoverValidation;
use App\Branch\Repository\BranchPostCreateRepo;
use Common\Enum\BranchStatus;
use Az\Route\Route;

#[Route(methods: 'post')]
class CreateSave extends BranchSaveAbstract
{
    public function _before()
    {
        parent::_before();
        $this->branch = $this->session->keep('branch') ?? new Branch;
    }

    #[BranchGenresValidation]
    public function genres()
    {
        $this->branch->genres = $this->data['genres'] ?? [];
        $this->session->branch = $this->branch;

        return $this->redirect(path('create', ['action' => $this->action]));
    }

    #[RulesValidation]
    public function rules($id = null)
    {
        parent::rules($id);
        $this->session->branch = $this->branch->update($this->data);

        return $this->redirect(path('create', ['action' => $this->action]));
    }

    #[BranchAuthorsValidation]
    public function authors(AddAuthorsRepo $repo, $id = null)
    {
        $this->branch->authors = $repo->getAuthorsByPost($this->data, $this->user->id);

        $master_id = $this->branch->authors->max('role')->id;
        $this->branch->master = $repo->find($master_id);
        
        $this->session->branch = $this->branch;

        return $this->redirect(path('create', ['action' => $this->action]));
    }

    #[BranchCoverValidation]
    public function cover(SaveCover $saveCover, $id = null)
    {
        parent::cover($saveCover, $id);
        $this->session->branch = $this->branch->update($this->data);

        return $this->redirect(path('create', ['action' => $this->action]));
    }

    public function publish(BranchPostCreateRepo $repo, SendInvitation $invitation)
    {
        $branch = $this->session->keep('branch');
        $branch->owner = $this->user->id;
        $path = path('private', ['action' => 'books']);

        switch ($this->action) {
            case 'cancel':
                $this->session->remove('branch');
                break;
            case 'publish':
                $branch->status = BranchStatus::Ready->value;
                $branch->id = $repo->save($branch, $this->data);

                if (isset($this->data['invitation']) && $this->data['invitation'] == '1') {
                    $msg_id = $invitation->send($branch);
                }

                break;
            case 'draft':
                $branch->status = BranchStatus::Draft->value;
                $repo->save($branch, $this->data);
                $this->session->remove('branch');
                break;
            default:
                $path = path('create', ['action' => $this->action]);
        }

        return $this->redirect($path);
    }
}
