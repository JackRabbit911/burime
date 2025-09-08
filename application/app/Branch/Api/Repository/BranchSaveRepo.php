<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

use App\Branch\Api\Model\ModelBranchSave;
use Common\Enum\AuthorRole;
use Common\Enum\BranchStatus;

class BranchSaveRepo
{
    private string $prefix = './img/branch/';

    public function __construct(private ModelBranchSave $model){}

    public function save(array $post, array $files, int $user_id)
    {
        $branch = $post['branch'];
        [$master_id, $authors] = $this->prepareBranchAuthors($branch['authors']);
        $genres = $branch['genres'];
        $posts = $post['posts'];

        unset($post, $branch['authors'], $branch['genres']);

        if (isset($files['bg_img'])) {
            $branch['info']['bg_img'] = 'background' . $this->getExt($files['bg_img']);
        }

        if (isset($files['cover'])) {
            $branch['info']['cover'] = 'cover' . $this->getExt($files['cover']);
        }

        $branch_id = $this->saveBranch($branch, $user_id);
        $this->model->saveBranchAuthors($authors, $branch_id);
        $this->model->saveBranchGenres($genres, $branch_id);
        $this->model->saveBranchPosts($posts, $branch_id, $master_id);
        $this->saveCover($files, $branch_id);

        return $files;
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
        if (isset($files['bg_img'])) {
                $this->saveUploadFile('background', $files['bg_img'], $branch_id);
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
        return match ($file->getClientMediaType()) {
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
        };
    }

    private function prepareBranchAuthors(array $authors)
    {
        foreach ($authors as &$author) {
            $author['author_id'] = $author['id'];
            
            if ($author['role'] === AuthorRole::getRole('master')) {
                $master = $author['id'];
            }

            unset($author['id'], $author['alias']);
        }

        return [$master, $authors];
    }
}
