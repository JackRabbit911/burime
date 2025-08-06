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
            $last = $this->makePostData($branch, $post['last_post'], true);
            $last['last'] = true;
            $pid = $this->client->post($path, ['post' => $last]);
        }

        return $branch->id;
    }

    private function makePostData(Branch $branch, string $body, bool $is_last = false)
    {
        return [
            'id' => null,
            'author_id' => $branch->master->id,
            'body' => $body,
            'status' => PostStatus::Publish->value,
            'branch_id' => $branch->id,
            'last' => $is_last,
        ];
    }
}
