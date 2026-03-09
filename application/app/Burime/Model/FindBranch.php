<?php declare(strict_types=1);

namespace App\Burime\Model;

use App\Author\Author;
use Common\Contract\BranchInterface;
use Common\Contract\IFindBranch;
use PDO;
use Psr\Container\ContainerInterface;
use Sys\Model\Model;

final class FindBranch extends Model implements IFindBranch
{
    protected string $table = 'branches';
    private string $entityClass;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->entityClass = $container->get(BranchInterface::class);
    }

    /**
     * PostControls
     */
    public function setStatus(int $branch_id, int $status)
    {
        $this->qb->table($this->table)
            ->where('id', '=', $branch_id)
            ->update(['status' => $status]);
    }

    public function findBranch($value): ?BranchInterface
    {
        $sql = "SELECT branches.*,
        GROUP_CONCAT(DISTINCT `authors`.`alias` ORDER BY ba.role DESC SEPARATOR ', ') AS alias,
        GROUP_CONCAT(DISTINCT `genres`.`title` ORDER BY genres.weight SEPARATOR ', ') AS genreStr,
        AVG(`pr`.`rating`) AS rating
        FROM branches
        LEFT JOIN branches_authors AS ba ON ba.branch_id = branches.id
        LEFT JOIN authors ON authors.id = ba.author_id
        LEFT JOIN branches_genres AS bg ON bg.branch_id = branches.id
        LEFT JOIN genres ON genres.id = bg.genre_id AND genres.weight > 0
        LEFT JOIN branches_posts AS bp ON bp.branch_id = branches.id
        LEFT JOIN posts_ratings AS pr ON pr.post_id = bp.post_id
        WHERE branches.id = ? AND ba.role > 100
        GROUP BY branches.id
        LIMIT 1";

        $pdo = $this->qb->pdo();
        $sth = $pdo->prepare($sql);
        $sth->setFetchMode(PDO::FETCH_CLASS, $this->entityClass);
        $sth->execute([$value]);
        $entity = $sth->fetch();

        return ($entity) ?: null;
    }

    public function getAuthorsByBranch($branch_id)
    {
        return $this->qb->table('authors')
            ->select('authors.*')
            ->select('branches_authors.role', 'branches_authors.status')
            ->join('branches_authors', 'branches_authors.author_id', '=', 'authors.id')
            ->where('branch_id', '=', $branch_id)
            ->orderBy('branches_authors.role', 'DESC')
            ->orderBy('branches_authors.status', 'DESC')
            ->asObject(Author::class)
            ->get();
    }
}
