<?php

declare(strict_types=1);

namespace App\Api\Branch\Repository;

use App\Api\Branch\Model\ModelBranchSave;
use Common\Enum\BranchAuthorStatus;
use Common\Enum\BranchStatus;

class BranchSaveRepo extends SaveRepo
{
    protected string $prefix = './img/branch/';

    public function __construct(private ModelBranchSave $model){}

    public function save(array $post, array $files, int $user_id)
    {
        $post['branch']['cover']['bg_img'] = (isset($files['bgImg']))
            ? 'background' . $this->getExt($files['bgImg']) : '';

        $post['branch']['cover']['cover'] = (isset($files['cover']))
            ? 'cover' . $this->getExt($files['cover']) : '';

        array_walk($post['members'], function(&$v) {
            unset($v['alias']);
            
            if ($v['status'] === BranchAuthorStatus::invited->value) {
                $v['status'] = BranchAuthorStatus::invited_informed->value;
            }
        });

        $branch_id = $this->saveBranch($post['branch'], $user_id);
        $this->model->saveBranchGenres($post['branch_genres'], $branch_id);
        $this->model->saveBranchAuthors($post['members'], $branch_id);
        $this->model->saveBranchPosts($post['posts'], $branch_id);
        $this->saveCover($files, $branch_id);

        return $branch_id;
    }

    private function saveBranch(array $branch, int $user_id)
    {
        if (!isset($branch['owner'])) {
            $branch['owner'] = $user_id;
        }

        $branch['cover'] = json_encode($branch['cover'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $branch['info'] = json_encode($branch['info'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $branch['status'] = BranchStatus::Ready->value;

        return (int) $this->model->saveBranch($branch);
    }
}
