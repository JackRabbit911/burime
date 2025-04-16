<?php declare(strict_types=1);

namespace App\Branch\Controller;

use App\Branch\Model\AddAuthorsRepo;
use App\Branch\Model\SaveCover;
use App\Branch\Middleware\BranchAuthorsValidation;
use App\Branch\Middleware\BranchCoverValidation;
use App\Branch\Middleware\BranchGenresValidation;
use App\Branch\Middleware\RulesValidation;
use App\Branch\Middleware\OwnerBranchGuard;
use Common\Observer\SendMsg;
use Common\Enum\BranchStatus;
use Az\Route\Route;
use HttpSoft\Response\RedirectResponse;

#[Route(methods: 'post')]
#[OwnerBranchGuard]
class EditSave extends BranchSaveAbstract
{
    public array $msg;

    protected function _before()
    {
        parent::_before();

        $this->branch = $this->request->getAttribute('branch');
        $status_msg =  __('Your changes have been saved. ')
        . '<a href="' . path('branch', ['branch_id' => $this->branch->id])
        . '" class="link">'
        . __('To show this branch') . '</a>';
        $this->session->flash('status_msg', $status_msg);
    }

    #[BranchGenresValidation]
    public function genres($id)
    {
        $this->branch->genres = $this->data['genres'] ?? [];
        $this->branch->save();

        return $this->redirect(path('edit', ['action' => __FUNCTION__, 'id' => $id]));
    }

    #[RulesValidation]
    public function rules($id)
    {
        parent::rules($id);
        $this->branch->update($this->data)->save();

        return $this->redirect(path('edit', ['action' => __FUNCTION__, 'id' => $id]));
    }

    #[BranchAuthorsValidation]
    #[SendMsg]
    public function authors(AddAuthorsRepo $repo, $id)
    {
        $this->branch->authors = $repo->getAuthors($this->session, $this->branch, $this->user->id, $this->data);

        $this->sendInvitation();
        $this->branch->save();

        return new RedirectResponse(path('edit', ['action' => __FUNCTION__, 'id' => $id]));
    }

    #[BranchCoverValidation]
    public function cover(SaveCover $saveCover, $id)
    {
        parent::cover($saveCover, $id);
        $this->branch->update($this->data)->save();

        return new RedirectResponse(path('edit', ['action' => __FUNCTION__, 'id' => $id]));
    }

    #[SendMsg]
    public function publish($id)
    {
        $this->branch->status = $this->data['status'] ?? BranchStatus::Ready->value;
        $this->branch->save();
        $this->sendInvitation();

        return new RedirectResponse(path('edit', ['action' => __FUNCTION__, 'id' => $id]));
    }

    private function sendInvitation()
    {
        $this->msg = [
            'to' => $this->session->keep('invites')->props()->all(),
            'from' => $this->branch->master()->id,
            'tpl' => 'invite_to_branch',
            'data' => [
                'branch_id' => $this->branch->id,
                'title' => $this->branch->title,
            ],
        ];
    }
}
