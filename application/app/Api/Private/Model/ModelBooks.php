<?php

declare(strict_types=1);

namespace App\Api\Private\Model;

use Common\Enum\BranchAuthorPermissions;
use Common\Enum\BranchAuthorStatus;
use Sys\Model\MysqlModel;
use PDO;

class ModelBooks extends MysqlModel
{
    public function get(int $user_id)
    {
        $master_role = BranchAuthorPermissions::EDIT_STATUS->value;
        
        $params = $this->qb->table('authors')
            ->select('id')
            ->where('owner', '=', $user_id)
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->get();

        $str = implode(',', array_fill(0, count($params), '?'));

        $params[] = BranchAuthorStatus::invited->value;

        $sql = "SELECT branches.id, branches.title, branches.cover, ba.role AS myRole,
        GROUP_CONCAT(DISTINCT `master`.`alias` SEPARATOR ', ') AS alias,
        GROUP_CONCAT(DISTINCT `genres`.`title` ORDER BY genres.weight SEPARATOR ', ') AS genreStr
        FROM branches_authors AS ba
        JOIN branches ON branches.id = ba.branch_id
        JOIN branches_authors AS bm ON bm.branch_id = branches.id AND bm.role & $master_role
        JOIN authors AS master ON master.id = bm.author_id
        JOIN branches_genres AS bg ON bg.branch_id = branches.id
        JOIN genres ON genres.id = bg.genre_id AND genres.weight > 0
        WHERE ba.author_id IN ($str) AND ba.status > ?
        GROUP BY branches.id, myRole
        ORDER BY myRole DESC, branches.created DESC";

        return $this->qb->query($sql, $params)
            ->get();
    }
}
