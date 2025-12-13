<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

use App\Branch\Api\BranchDTO;
use App\Branch\Api\FirstLastDTO;
use App\Branch\Api\Model\ModelDraft;

class DraftRepo extends ParentRepo
{
    protected string $prefix = STORAGE . 'uploads/draft/';

    public function __construct(private ModelDraft $model) {}

    public function getBootstrap(int $id)
    {
        $params = $this->model->get($id);

        if (!$params) {
            return null;
        }
        
        $data['branch'] = new BranchDTO(json_decode($params['branch'], true));
        $data['branch_genres'] = json_decode($params['genres'], true);
        $data['members'] = json_decode($params['members']);
        $data['posts'] = new FirstLastDTO((array) json_decode($params['posts']));
        $data['draft'] = $id;

        return $data;
    }
}
