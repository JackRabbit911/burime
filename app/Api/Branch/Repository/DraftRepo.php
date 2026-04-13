<?php

declare(strict_types=1);

namespace App\Api\Branch\Repository;

use App\Api\Branch\BranchDTO;
use App\Api\Branch\FirstLastDTO;
use App\Api\Branch\Model\ModelAuthors;
use App\Api\Branch\Model\ModelBranch;
use App\Api\Branch\Model\ModelDraft;

class DraftRepo extends ParentRepo
{
    protected string $prefix = STORAGE . 'uploads/draft/';

    public function __construct(
        protected ModelBranch $modelBranch,
        protected ModelAuthors $modelAuthors,
        private ModelDraft $model
    )
    {
        parent::__construct($modelBranch, $modelAuthors);
    }

    public function get(int|string $id)
    {
        $params = $this->model->get((int) $id);

        if (!$params) {
            return null;
        }
        
        $data['branch'] = new BranchDTO(json_decode($params['branch'], true));
        $data['branch_genres'] = json_decode($params['genres'], true);
        $data['members'] = json_decode($params['members']);
        $data['posts'] = new FirstLastDTO((array) json_decode($params['posts']));
        $data['draft'] = (int) $id;

        return $data;
    }
}
