<?php declare(strict_types=1);

namespace App\Author\Model;

use Common\Contract\IModelUserGroup;
use Sys\Model\Model;

final class ModelUserGroup extends Model implements IModelUserGroup
{
    public function addToUserGroup($user_id, $author_id, $role)
    {
        if (is_array($author_id)) {
            $data = array_map(function ($id) use ($user_id, $role) {
                return [
                    'user_id' => (int) $user_id,
                    'author_id' => (int) $id,
                    'role' => $role,
                ];
            }, $author_id);
        } else {
            $data = [
                'user_id' => (int) $user_id,
                'author_id' => (int) $author_id,
                'role' => $role,
            ];
        }

        return $this->qb->table('users_authors')
            ->insertIgnore($data);
    }

    public function removeFromUserGroup($user_id, $author_id, $role)
    {
        return $this->qb->table('users_authors')
            ->where('user_id', '=', $user_id)
            ->where('author_id', '=', $author_id)
            ->where('role', '=', $role)
            ->delete();
    }

    public function inUserGroup($user_id, $author_id, $role)
    {
        $favorite = $this->qb->table('users_authors')
            ->select('author_id')
            ->where('user_id', '=', $user_id)
            ->where('author_id', '=', $author_id)
            ->where('role', '=', $role)
            ->first();

        return ($favorite) ? true : false;
    }

    // public function getUserGroupMembers($user_id, MemberRole $role)
    // {
    //     return $this->qb->table('users_authors')
    //         ->select('authors.*')
    //         ->join('authors', 'authors.id', '=', 'author_id')
    //         ->where('user_id', '=', $user_id)
    //         ->where('role', '=', $role->value)
    //         ->asObject(Author::class)
    //         ->get();
    // }
}
