<?php declare(strict_types=1);

namespace App\Burime\Component;

use App\Branch\Branch;
use App\Burime\Model\FindBranch;
use Sys\Template\Component;

class Cover extends Component
{
    private Branch $branch;

    public function __construct(FindBranch $model, int $branch_id)
    {
        $this->branch = $model->findBranch($branch_id);
    }

    public function render()
    {
        return view('burime/cover', ['book' => $this->branch]);
    }
}
