<?php declare(strict_types=1);

namespace App\Burime\Component;

use Common\Contract\BranchInterface;
use Common\Enum\AuthorRole;

use Common\Enum\BranchAuthorStatus;
use Sys\Template\Component;

class BranchAuthors extends Component
{
    private array $authors;
    private string $view = 'web/branch/authors';

    public function __construct(BranchInterface $branch)
    {
        $this->authors = $branch->authors->map(function ($v) {
            $v->role = AuthorRole::getRoleString($v->role);
            $v->status = BranchAuthorStatus::getStatusString($v->status);
            return $v;
        })->all();
    }

    public function render()
    {
        return view($this->view, ['authors' => $this->authors]);
    }
}
