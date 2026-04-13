<?php

declare(strict_types=1);

namespace App\Api\Message\Model;

use Sys\Model\MysqlModel;
use PDO;

class ModelAdditional extends MysqlModel
{
    public function getBranchCover(int $id)
    {
        $sql = "SELECT branches.id, branches.title, branches.cover,
        GROUP_CONCAT(DISTINCT `genres`.`title` ORDER BY genres.weight SEPARATOR ', ') AS genreStr
        FROM branches
        JOIN branches_genres AS bg ON bg.branch_id = branches.id
        JOIN genres ON genres.id = bg.genre_id AND genres.weight > 0
        WHERE branches.id = ?
        GROUP BY branches.id";

        return $this->qb->query($sql, [$id])
            ->first();
    }

    public function getAuthorAlias(int $branch_id)
    {
        $sql = "SELECT a.alias FROM branches_authors AS `ba`
        JOIN authors AS a ON a.id = ba.author_id
        WHERE ba.branch_id = ?
        AND ba.role = (SELECT MAX(ba.role) FROM branches_authors)
        ";

        return $this->qb->query($sql, [$branch_id])
            ->setFetchMode(PDO::FETCH_COLUMN)
            ->first();
    }
}
