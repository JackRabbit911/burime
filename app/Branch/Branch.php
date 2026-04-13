<?php declare(strict_types=1);

namespace App\Branch;

use Common\Enum\AuthorRole;
use App\Branch\Model\ModelBranch;
use Common\Contract\BranchInterface;
use Sys\Entity\Entity;

#[ModelBranch]
class Branch extends Entity implements BranchInterface
{
    const COVERPATH = DOCROOT . 'img/cover/';
    
    protected int $id;

    public function __construct()
    {
        if (isset($this->info)) {
            $this->info = json_decode($this->info, true);
        }

        if (isset($this->cover)) {
            $this->cover = json_decode($this->cover, true);
        }

        if (isset($this->rating)) {
            $this->rating = round((float) $this->rating, 1);
        }
    }

    public function prepareProps()
    {
        if (isset($this->info)) {
            $this->info = json_encode($this->info, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

        if (isset($this->cover)) {
            $this->cover = json_encode($this->cover, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

        return $this;
    }

    public function master()
    {
        return $this->authors->getInstance(AuthorRole::Master->value, 'role');
    }
}
