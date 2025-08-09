<?php declare(strict_types=1);

namespace App\Burime\Controller;

use App\Burime\Model\BranchAuthor;
use Common\Enum\AuthorRole;
use Common\Enum\BranchAuthorStatus;
use Common\Contract\IModelGroup;
use HttpSoft\Response\RedirectResponse;
use Sys\Contract\UserInterface;
use Sys\Controller\BaseController;
use Az\Route\Route;

class Participation extends BaseController
{
    private IModelGroup $modelGroup;
    private BranchAuthor $modelBranchAuthor;
    private ?UserInterface $user;

    public function __construct(IModelGroup $modelGroup, BranchAuthor $modelBranchAuthor)
    {
        $this->modelGroup = $modelGroup;
        $this->modelBranchAuthor = $modelBranchAuthor;
    }

    protected function _before()
    {
        $this->user = $this->request->getAttribute('user');
    }

    public function accept($branch_id, $author_id)
    {
        $this->setAuthorStatus($branch_id, $author_id, BranchAuthorStatus::Participant);
        $uri = path('branch', ['branch_id' => $branch_id]);
        return new RedirectResponse($uri);
    }

    public function refuse($branch_id, $author_id)
    {
        $this->setAuthorStatus($branch_id, $author_id, BranchAuthorStatus::Refused);
        $uri = path('message', ['action' => 'list']);
        return new RedirectResponse($uri);
    }

    public function recall($branch_id, $author_id)
    {
        if ($this->modelGroup->inGroup($this->user->id, $author_id)) {
            $this->modelBranchAuthor->deleteAuthor((int) $branch_id, (int) $author_id);
        }
        
        $uri = path('branch', ['branch_id' => $branch_id]);
        return new RedirectResponse($uri);
    }

    #[Route(methods: 'post')]
    public function ask2join($branch_id, $author_id = 0)
    {
        $data['branch_id'] = $branch_id;
        $data['author_id'] = (int) $this->request->getParsedBody()['author'];
        $data['user_id'] = $this->user->id;
        $data['role'] = AuthorRole::Author->value;
        $data['status'] = BranchAuthorStatus::Candidate->value;

        $this->modelBranchAuthor->addAuthor($data);

        $uri = path('branch', ['branch_id' => $branch_id, 'action' => 'authors']);
        return new RedirectResponse($uri);
    }

    private function setAuthorStatus($branch_id, $author_id, $status)
    {
        if ($this->modelGroup->inGroup($this->user->id, $author_id)) {
            $this->modelBranchAuthor->setAuthorStatus((int) $branch_id, (int) $author_id, $this->user->id, $status);
        }
    }
}
