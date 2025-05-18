<?php 

declare(strict_types=1);

namespace App\Burime\Controller;

use App\Burime\Model\ModelPost;
use App\Burime\Repository\BranchRepo;
use Sys\Controller\BaseController;
use Az\Route\Route;
use HttpSoft\Response\TextResponse;

class Api extends BaseController
{
    #[Route(methods: ['post'])]
    public function savepost(ModelPost $model)
    {
        $post = $this->request->getParsedBody();
        $pid = $model->save($post);

        return new TextResponse($pid);
    }

    public function getbranch(BranchRepo $repo, $id)
    {
        $branch = $repo->find($id);
        return new TextResponse(serialize($branch));
    }
}
