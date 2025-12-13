<?php

declare(strict_types=1);

namespace App\Branch\Api\Repository;

use App\Branch\Api\Model\ModelDraft;

class DraftSaveRepo extends SaveRepo
{
    protected string $prefix = STORAGE . 'uploads/draft/';

    public function __construct(private ModelDraft $model){}

    public function save(array $post, array $files, int $user_id): int
    {
        if (isset($post['draft'])) {
            $data['id'] = $post['draft'];
        }

        if (!isset($post['branch']['owner'])) {
            $post['branch']['owner'] = $user_id;
        }

        $data['owner'] = $post['branch']['owner'];
        $data['title'] = $post['branch']['title'];
        $data['branch'] = json_encode($post['branch'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $data['genres'] = json_encode($post['branch_genres'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $data['members'] = json_encode($post['members'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $data['posts'] = json_encode($post['posts'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $id = $this->model->save($data);
        $this->saveCover($files, $id);

        return $id;
    }
}
