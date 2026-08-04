<?php

declare(strict_types=1);

namespace Adm\Repository;

use Adm\Model\ModelUsers;
use Adm\Service\ADM;
use App\Api\Private\Model\ModelAuthors;
use App\Api\Private\Model\ModelBooks;
use Auth\Component\Avatar;

class UserRepo
{
    public function __construct(
        private ModelUsers $modelUsers,
        private ModelAuthors $modelAuthors,
        private ModelBooks $modelBooks,
    ) {}

    public function getUser(int $adm_role, int $id)
    {
        $user = $this->modelUsers->find($id);
        $own_authors_ids = $this->modelAuthors->getOwnAuthorsIds($id);

        $authors = [
            'total' => $this->modelAuthors->getGroupsCount($own_authors_ids),
            'own' => count($own_authors_ids),
            'allow' => ADM::is($adm_role, ADM::BURIME),
            'list' => ADM::is($adm_role, ADM::BURIME)
                ? $this->modelAuthors->getMyGroups($id, $own_authors_ids) : [],
        ];

        $books = [
            'total' => $this->modelBooks->getCount($own_authors_ids),
            'own' => $this->modelBooks->getOwnCount($user->id, $own_authors_ids),
            'allow' => ADM::is($adm_role, ADM::BURIME),
            'list' =>  ADM::is($adm_role, ADM::BURIME)
                ? $this->modelBooks->get($own_authors_ids) : [],
        ];

        $user->authors = (object) $authors;
        $user->books = (object) $books;
        $user->avatarUrl = Avatar::getSrc($id);

        $user->admRole = $adm_role;

        return $user;
    }
}
