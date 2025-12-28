<?php

declare(strict_types=1);

namespace App\Api\Author\Repository;

use App\Api\Author\Model\ModelSaveDelete;
use App\Api\Common\Helper\Facade\UploadFile;
use HttpSoft\Message\UploadedFile;
use Sys\Helper\Facade\Dir;

class AuthorSaveRepo
{
    private string $dir = './avatar/author/';

    public function __construct(private ModelSaveDelete $model) {}

    public function savePost(array $post, int $user_id)
    {
        $post['info'] = json_encode($post['info'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $post['owner'] = $user_id;

        return $this->model->save($post);
    }

    public function saveFile(?UploadedFile $file, int $user_id)
    {
        if (!$file) {
            return;
        }

        Dir::clearByMask($this->dir, "$user_id.*");
        UploadFile::save($file, $this->dir, $user_id);
    }
}
