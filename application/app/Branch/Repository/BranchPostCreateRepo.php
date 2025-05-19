<?php declare(strict_types=1);

namespace App\Branch\Repository;

use App\Branch\Branch;
use App\Branch\Model\ModelBranch;
use Common\Enum\PostStatus;
use Sys\Request\Internal\Wrapper;

class BranchPostCreateRepo
{
    public function __construct(
        private ModelBranch $modelBranch,
        private Wrapper $client
    ){}

    public function save(Branch $branch, array $post)
    {    
        $branch->id = $this->modelBranch->save($branch);
        $path = path('int.burime', ['action' => 'savepost']);
        
        if (!empty($post['first_post'])) {
            $first = $this->makePostData($branch, $post['first_post']);          
            $pid = $this->client->post($path, ['post' => $first]);
        }

        if (!empty($post['last_post'])) {
            $last = $this->makePostData($branch, $post['last_post']);
            $last['last'] = true;
            $pid = $this->client->post($path, ['post' => $first]);
        }

        return $branch->id;
    }

    private function makePostData(Branch $branch, string $post)
    {
        return [
            'id' => (int) $post['first_id'] ?? null,
            'author_id' => $branch->master->id,
            'body' => $post['first_post'],
            'status' => PostStatus::Publish->value,
            'branch_id' => $branch->id,
        ];
    }
}
