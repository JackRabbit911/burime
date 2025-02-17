<?php declare(strict_types=1);

namespace App\Author\Model;

use App\Author\Author;
use Common\Enum\MemberRole;
use Common\Enum\AuthorRole;
use Common\Enum\BranchAuthorStatus;
use Sys\Model\Interface\Saveble;
use Sys\Model\Trait\QueryBuilder;
use Sys\Model\Trait\Schema;
use Sys\Collection\Collection;

class ModelAuthor implements Saveble
{
    use QueryBuilder;
    use Schema;

    private string $table = 'authors';
    
    /**
     * CommitListener::handle
     */
    public function save(Author|array $data): mixed
    { 
        if (!is_array($data)) {
            if (method_exists($data, 'prepareProps')) {
                $data->prepareProps();
            }

            if (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            } else {
                $data = (array) $data;
            }
        }

        if (isset($data['member'])) {
            $this->setMember($data['id'], $data['member'], 100);
            unset($data['member']);
        }

        $data = array_intersect_key($data, array_flip($this->columns($this->table)));

        return $this->qb->table($this->table)
            ->onDuplicateKeyUpdate($data)
            ->insert($data);
    }

    /**
     * Controller\Author::_before
     * Middleware\OwnerGuard::process
     */
    public function find($id)
    {
        return $this->qb->table($this->table)
            ->select('authors.*')
            ->select($this->qb->raw('COUNT(child_id) AS c_members'))
            ->select($this->qb->raw('JSON_OBJECTAGG(child_id, au.alias) AS j_members'))
            ->select($this->qb->raw('JSON_ARRAYAGG(au.owner) AS o_members'))
            ->join('authors_authors', 'parent_id', '=', 'id')
            ->join($this->qb->raw('authors AS au'), 'au.id', '=', 'child_id')
            ->where('role', '>', 40)
            ->asObject(Author::class)
            ->find($id, 'authors.id');
    }

    /**
     * Controller\AuthorsList::makeData
     */
    public function get($limit = null, $offset = 0, $groupsOnly = false)
    {
        $table = $this->qb->table($this->table)
            ->select('authors.*')
            ->select($this->qb->raw('AVG(authors_ratings.rating) AS rating'))
            ->select($this->qb->raw('COUNT(child_id) AS c_members'))
            ->leftJoin('authors_authors', 'parent_id', '=', 'id')
            ->leftJoin('authors_ratings', 'author_id', '=', 'id')
            ->groupBy('authors.id')
            ->orderBy('rating', 'DESC');

        if ($groupsOnly) {
            $table->where('openclosed', '<', 2);
        }

        if ($limit) {
            $table->limit($limit)->offset($offset);
        }
        
        return $table->asObject(Author::class)->get();
    }

    /**
     * Controller\AuthorsList::__construct
     */
    public function getCount()
    {
        return $this->qb->table($this->table)->count();
    }

    /**
     * Controller\Author::members
     */
    public function getMembers($author_id)
    {
        return $this->qb->table('authors_authors')
            ->alias('aa')
            ->select('authors.*', 'aa.role')
            ->join('authors', 'authors.id', '=', 'aa.child_id')
            ->where('aa.parent_id', '=', $author_id)
            ->orderBy('aa.role', 'DESC')
            ->asObject(Author::class)
            ->get();
    }

    /**
     * UserAuthorsMiddleware
     */
    public function getByUser($user_id)
    {
        $array = $this->qb->table('authors')
            ->where('owner', '=', $user_id)
            ->asObject(Author::class)->get();

        return new Collection($array);
    }

    /**
     * BranchAuthorsRepo
     */
    public function getAuthorsIdsByOwners($owners)
    {
        if (empty($owners)) {
            return [];
        }
        
        return $this->qb->table('authors')
            ->selectDistinct('id')
            ->whereIn('owner', $owners)
            ->setFetchMode(\PDO::FETCH_COLUMN)
            ->get();
    }

    /**
     * BranchAuthorsRepo
     */
    public function getByFilter($filter = null, $except = [])
    {
        $role = MemberRole::getByFilter($filter);

        $table = $this->qb->table('authors')
            ->selectDistinct('id')
            ->select('alias');

        if ($role) {
            $table->join('users_authors', 'users_authors.author_id', '=', 'authors.id')
                ->where('role', '=', $role);
        }

        if (!empty($except)) {
            $table->whereNotIn('id', $except);
        }

        return $table->get();
    }

    public function findByBranch($author_id, $branch_id, $is_master = false)
    {
        $author = $this->qb->table('authors')
            ->asObject(Author::class)
            ->find($author_id);

        $branch_info = $this->qb->table('branches_authors')
            ->where('branch_id', '=', $branch_id)
            ->find($author_id, 'author_id');

        if ($is_master) {
            $default_role = AuthorRole::Master->value;
            $default_status = BranchAuthorStatus::Participant->value;
        } else {
            $default_role = $branch_info->role ?? AuthorRole::Author->value;
            $default_status = $branch_info->status ?? BranchAuthorStatus::Invited->value;
        }

        $author->role = $default_role;
        $author->status = $default_status;

        return $author;
    }

    /**
     * BranchAuthorsRepo
     */
    public function getByIds(array $ids)
    {
        return ($ids) ? $this->qb->table($this->table)
            ->select('id', 'alias')
            ->whereIn('id', $ids)
            ->get() : [];
    }
}
