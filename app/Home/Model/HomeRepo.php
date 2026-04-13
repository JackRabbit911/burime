<?php declare(strict_types=1);

namespace App\Home\Model;

use App\Home\Model\ModelAuthors;
use App\Home\Model\ModelBestPost;
use App\Home\Model\ModelWorks;
use Sys\Helper\Facade\Text;

class HomeRepo
{
    public function __construct(
        private ModelWorks $modelWorks,
        private ModelAuthors $modelAuthor,
        private ModelBestPost $modelBestPost
    ) {}

    public function getBranchesCount()
    {
        return $this->modelWorks->getCount();
    }

    public function getBranches($limit)
    {
        return $this->modelWorks->get($limit);
    }

    public function getAuthorsCount()
    {
        return $this->modelAuthor->getCount();
    }

    public function getAuthors($limit)
    {
        return $this->modelAuthor->get($limit);
    }

    public function getBestPost()
    {
        $post = $this->modelBestPost->getPost();

        if ($post) {
            $post->body = Text::catStr($post->body, 500);
        }

        return $post;
    }
}
