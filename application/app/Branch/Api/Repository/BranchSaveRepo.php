<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

use App\Branch\Api\Model\ModelBranchSave;
use Common\Enum\BranchStatus;

class BranchSaveRepo extends SaveRepo
{
    protected string $prefix = './img/branch/';

    public function __construct(private ModelBranchSave $model){}

    public function save(array $post, array $files, int $user_id)
    {
        if (isset($files['bgImg'])) {
            $post['branch']['info']['bg_img'] = 'background' . $this->getExt($files['bgImg']);
        } else {
            $post['branch']['info']['bg_img'] = '';
        }

        if (isset($files['cover'])) {
            $post['branch']['info']['cover'] = 'cover' . $this->getExt($files['cover']);
        } else {
            $post['branch']['info']['cover'] = '';
        }

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

        $branch['info'] = json_encode($branch['info'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $branch['status'] = BranchStatus::Ready->value;

        return (int) $this->model->saveBranch($branch);
    }
}
