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
        $author = $post['author'];
        $members = $post['members'] ?? [];

        $author['info'] = json_encode($author['info'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $author['owner'] = $user_id;

        $author_id = $this->model->saveAuthor($author);
        $this->model->saveMembers($author_id, $members);

        return $author_id;
    }

    public function saveFile(?UploadedFile $file, int $author_id)
    {
        if (!$file) {
            return;
        }
        
        Dir::clearByMask($this->dir, "$author_id.*");
        UploadFile::save($file, $this->dir, $author_id);
    }
}
