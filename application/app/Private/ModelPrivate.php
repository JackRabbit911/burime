<?php declare(strict_types=1);

namespace App\Private;

use Common\Contract\AuthorInterface;
use Common\Contract\BranchInterface;
use Common\Enum\MemberRole;
// use Common\Enum\AuthorRole;
use Common\Enum\BranchAuthorPermissions;
use Common\Enum\BranchAuthorStatus;

use Sys\Model\Model;
use Psr\Container\ContainerInterface;
use PDO;

class ModelPrivate extends Model
{
    private string $branchClass;
    private string $authorClass;
    private int $status;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->branchClass = $container->get(BranchInterface::class);
        $this->authorClass = $container->get(AuthorInterface::class);
        $this->status = BranchAuthorStatus::invited->value;
    }

    public function getMyBooks($user_id)
    {
        $master_role = BranchAuthorPermissions::EDIT_STATUS->value;
        
        $params = $this->qb->table('authors')
            ->select('id')
            ->where('owner', '=', $user_id)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();

        $str = implode(',', array_fill(0, count($params), '?'));

        $params[] = $this->status;

        $sql = "SELECT branches.*, ba.role AS author_role,
        GROUP_CONCAT(DISTINCT `master`.`alias` SEPARATOR ', ') AS alias,
        GROUP_CONCAT(DISTINCT `genres`.`title` ORDER BY genres.weight SEPARATOR ', ') AS genreStr
        FROM branches_authors AS ba
        JOIN branches ON branches.id = ba.branch_id
        JOIN branches_authors AS bm ON bm.branch_id = branches.id AND bm.role & $master_role
        JOIN authors AS master ON master.id = bm.author_id
        JOIN branches_genres AS bg ON bg.branch_id = branches.id
        JOIN genres ON genres.id = bg.genre_id AND genres.weight > 0
        WHERE ba.author_id IN ($str) AND ba.status > ?
        GROUP BY branches.id, author_role
        ORDER BY author_role DESC, branches.created DESC";

        return $this->qb->query($sql, $params)
            ->asObject($this->branchClass)
            ->get();
    }

    public function getUserGroupMembers($user_id, MemberRole $role)
    {
        return $this->qb->table('users_authors')
            ->select('authors.*')
            ->join('authors', 'authors.id', '=', 'author_id')
            ->where('user_id', '=', $user_id)
            ->where('role', '=', $role->value)
            ->asObject($this->authorClass)
            ->get();
    }

    public function getMyGroups($user_id)
    {
        $myGoupsIds = $this->qb->table('authors')
        ->select('id')
        ->where('owner', '=', $user_id)
        ->setFetchMode(PDO::FETCH_COLUMN)->get();
        
        if (empty($myGoupsIds)) {
            return [];
        }

        $groups = $this->qb->table('authors_authors')->alias('aa')
            ->select('authors.*')
            ->join('authors', 'authors.id', '=', 'aa.parent_id')
            ->whereIn('aa.child_id', $myGoupsIds)
            ->where('aa.status', '>=', $this->status)
            // ->where('aa.role', '>=', AuthorRole::Author->value)
            ->asObject($this->authorClass)
            ->orderBy('openclosed', 'DESC');
            
        return $this->qb->table('authors')
            ->where('owner', '=', $user_id)
            ->asObject($this->authorClass)
            ->orderBy('openclosed', 'DESC')
            ->union($groups)
            ->get();
    }
}
