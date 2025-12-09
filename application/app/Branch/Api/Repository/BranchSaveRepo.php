<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

use App\Branch\Api\Model\ModelBranchSave;
use Common\Enum\BranchStatus;

class BranchSaveRepo
{
    private string $prefix = './img/branch/';

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

    private function saveCover(array $files, int $branch_id)
    {
        if (isset($files['bgImg'])) {
            $this->saveUploadFile('background', $files['bgImg'], $branch_id);
        } else {
            $this->deleteFile('background', $branch_id);
        }

        if (isset($files['cover'])) {
            $this->saveUploadFile('cover', $files['cover'], $branch_id);
        } else {
            $this->deleteFile('cover', $branch_id);
        }
            
    }

    private function saveUploadFile($filename, $file, $dir)
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return false;
        }

        $dir = $this->prefix . $dir;

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!is_writable($dir)) {
            chmod($dir, 0777);
        }

        $filename = $dir . '/' . $filename . $this->getExt($file);
        $file->moveTo($filename);
        chmod($filename, 0777);

        return true;
    }

    private function deleteFile($pattern, $dir)
    {
        $pattern = $this->prefix . $dir . '/' . $pattern . '.*';
        array_map('unlink', glob($pattern));
    }

    private function getExt($file) {
        return '.' . str_replace('image/', '', $file->getClientMediaType());
    }
}
