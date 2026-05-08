<?php

declare(strict_types=1);

namespace App\Api\Author\Repository;

use App\Api\Author\Model\ModelAuthorSave;
use App\Api\Common\Helper\Facade\UploadFile;
use Common\Enum\BranchAuthorStatus;
use Sys\Helper\Facade\Dir;
use HttpSoft\Message\UploadedFile;

class AuthorSaveRepo
{
    private string $dir = './avatar/author/';

    public function __construct(private ModelAuthorSave $model) {}

    public function savePost(array $post, int $user_id)
    {
        $author = $post['author'];
        $members = $post['members'] ?? [];

        array_walk($members, function(&$v) {
            unset($v['alias']);
            
            if ($v['status'] === BranchAuthorStatus::invited->value) {
                $v['status'] = BranchAuthorStatus::invited_informed->value;
            }
        });

        $author['info'] = json_encode($author['info'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $author['owner'] = $user_id;

        $author_id = $this->model->saveAuthor($author);
        $this->model->saveMembers($author_id, $members);

        return $author_id;
    }
}
