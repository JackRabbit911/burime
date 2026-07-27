<?php

declare(strict_types=1);

namespace Adm\Repository;

use Adm\Model\ModelUsers;
use App\Api\Private\Model\ModelAuthors;
// use App\Api\Common\Model\ModelAuthors;
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
        $user = $this->modelUsers->read($id);
        $own_authors_ids = $this->modelAuthors->getOwnAuthorsIds($id);

        $authors = [
            'total' => $this->modelAuthors->getGroupsCount($own_authors_ids),
            'own' => count($own_authors_ids),
            'allow' => $this->access($adm_role, ADM_BURIME),
            'list' => $this->access($adm_role, ADM_BURIME)
                ? $this->modelAuthors->getMyGroups($id, $own_authors_ids) : [],
        ];

        $books = [
            'total' => $this->modelBooks->getCount($own_authors_ids),
            'own' => $this->modelBooks->getOwnCount($user->id, $own_authors_ids),
            'allow' => $this->access($adm_role, ADM_BURIME),
            'list' =>  $this->access($adm_role, ADM_BURIME)
                ? $this->modelBooks->get($own_authors_ids) : [],
        ];

        $user->authors = (object) $authors;
        $user->books = (object) $books;
        $user->avatarUrl = Avatar::getSrc($id);

        $user->admRole = $adm_role;

        return $user;
    }

    private function access(int $adm_role, int $role, $user_role = null)
    {
        return $adm_role & $role ? true : false;
    }

    // private function makeAuthorsList(int $id, array $own_authors_ids)
    // {
    //     $authors = $this->modelAuthors->getMyGroups($id, $own_authors_ids);

    //     return array_map(function($v) {
    //         return [
    //             'id' => $v->id,
    //             'label' => $v->alias,
    //         ];
    //     }, $authors);
    // }

    // private function makeBooksList(array $own_authors_ids)
    // {
    //     $authors = $this->modelBooks->get($own_authors_ids);

    //     return array_map(function($v) {
    //         return [
    //             'id' => $v->id,
    //             'label' => $v->title,
    //         ];
    //     }, $authors);
    // }
}
