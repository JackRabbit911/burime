<?php declare(strict_types=1);

namespace App\Message\Model;

use App\Message\Msg;
use Common\Contract\IModelMessage;
use Sys\Model\Interface\Saveble;
use Sys\Model\Model;
use Pecee\Pixie\Exceptions\ForeignKeyException;
use PDO;
use JSON_UNESCAPED_SLASHES;
use JSON_UNESCAPED_UNICODE;

final class ModelMessage extends Model implements Saveble, IModelMessage
{
    protected string $table = 'messages';

    public function getInbox($ids)
    {
        return $this->qb->table('messages_authors')->alias('to')
            ->select('messages.id', 'messages.created')
            ->select('from', 'subject')
            ->select('authors.alias')
            ->select('to.status')
            ->select('to.author_id')
            ->select($this->qb->raw('au.alias AS `to_alias`'))
            ->join('messages', 'messages.id', '=', 'to.message_id')
            ->join('authors', 'authors.id', '=', 'messages.from')
            ->join($this->qb->raw('authors AS au ON au.id = to.author_id'))
            ->whereIn('to.author_id', $ids)
            ->orderBy('to.status', 'DESC')
            ->orderBy('messages.created', 'DESC')
            ->get();
    }

    public function getOutbox($ids)
    {
        return $this->qb->table($this->table)
            ->select(['messages.id', 'subject', 'messages.created'])
            ->select($this->qb->raw('au.alias AS `from`'))
            ->select($this->qb->raw('MAX(messages_authors.status) AS `status`'))
            ->select($this->qb->raw('JSON_ARRAYAGG(authors.alias) AS `to`'))
            ->join('messages_authors', 'message_id', '=', 'messages.id')
            ->join('authors', 'authors.id', '=', 'messages_authors.author_id')
            ->join($this->qb->raw('authors AS au ON au.id = `from`'))
            ->whereIn('from', $ids)
            ->groupBy('messages.id')
            ->orderBy('status', 'DESC')
            ->orderBy('messages.created', 'DESC')
            ->get();
        }
        
    public function getDeleted($ids)
    {
        return $this->qb->table($this->table)
            ->select('messages.id', 'from', 'subject')
            ->select('authors.alias')
            ->join('authors', 'authors.id', '=', 'from')
            ->leftJoin('messages_authors', 'message_id', '=', 'messages.id')
            ->whereNull('message_id')
            ->whereIn('from', $ids)
            ->get();
    }

    public function find($id)
    {
        return $this->qb->table($this->table)
            ->select('messages.*')
            ->select('authors.alias')
            ->select('messages_authors.status')
            ->leftJoin('messages_authors', 'message_id', '=', 'messages.id')
            ->join('authors', 'authors.id', '=', 'from')
            ->find($id, 'messages.id');
    }

    public function getSubject($id)
    {
        return $this->qb->table($this->table)
            ->select('messages.subject')
            ->setFetchMode(\PDO::FETCH_COLUMN)
            ->find($id);
    }

    public function getRecipients($msg_id, $except = null)
    {
        $table = $this->qb->table('messages_authors')
            ->selectDistinct('messages_authors.status')
            ->select('authors.id', 'authors.alias')
            ->join('authors', 'authors.id', '=', 'author_id')
            ->where('message_id', '=', $msg_id);

        if ($except) {
            $table->where('author_id', '<>', $except);
        }    
        
        return $table->get();
    }

    public function save(Msg|array $data)
    {
        if ($data instanceof Msg) {
            $data = $data->prepareProps()->toArray();
        }

        unset($data['path']);

        $status = (isset($data['important'])) ? 120 : 100;
        unset($data['important']);

        if (empty($data['to'])) {
            return;
        }

        $recipients = $data['to'];
        unset($data['to']);
        unset($data['tpl']);

        $id = $this->qb->table($this->table)
            ->insert($data);

        foreach ($recipients as $to) {
            $msgs_authors[] = [
                'message_id' => $id,
                'author_id' => (int) $to,
                'status' => $status,
            ];
        }

        $this->qb->table('messages_authors')
            ->insert($msgs_authors);

        return $id;    
    }

    public function changeStatus($id, $author_id, $status)
    {
        $this->qb->table('messages_authors')
            ->where('message_id' , '=', $id)
            ->where('author_id' , '=', $author_id)
            ->update(['status' => $status]);
    }

    public function delete($id, $author_id)
    {
        $this->qb->table('messages_authors')
            ->where('message_id' , '=', $id)
            ->where('author_id' , '=', $author_id)
            ->delete();
    }

    public function remove($id, $from)
    {
        try {
            $this->qb->table($this->table)
                ->where('id', '=', $id)
                ->where('from', '=', $from)
                ->delete();
                return true;
        } catch (ForeignKeyException $e) {
            return false;
        }
    }

    public function forceDelete($id)
    {
        $this->qb->table('messages_authors')
            ->where('message_id', '=', $id)
            ->delete();

        $this->qb->table($this->table)
            ->where('id', '=', $id)
            ->delete();
    }


    // public function getUsersGroupsIds($user_id)
    // {
    //     $myGroupsIds = $this->qb->table('authors')
    //         ->select('id')
    //         ->where('owner', '=', $user_id)
    //         ->setFetchMode(PDO::FETCH_COLUMN);

    //     $usersGroupsIds = $this->qb->table('users_authors')
    //         ->select('author_id')
    //         ->where('user_id', '=', $user_id)
    //         ->where('role', '>', 200)
    //         ->setFetchMode(PDO::FETCH_COLUMN);

    //     $authors_authors = $this->qb->table('authors_authors')->alias('aa')
    //         ->select('authors.id')
    //         ->join('authors', 'authors.id', '=', 'aa.parent_id')
    //         ->where('aa.role', '>=', 100)
    //         ->setFetchMode(PDO::FETCH_COLUMN)
    //         ->orderBy('openclosed', 'DESC')
    //         ->union($usersGroupsIds)
    //         ->union($myGroupsIds);
            
    //     $usersGroupsIdsArray = $usersGroupsIds->get();

    //     if (!empty($usersGroupsIdsArray)) {
    //         $authors_authors->whereIn('aa.child_id', $usersGroupsIdsArray);
    //     }

    //     return $authors_authors->get();
    // }

    // private function getAuthorsUsersIds($authors)
    // {
    //     if (empty($authors)) {
    //         return [];
    //     }

    //     if (!is_array($authors)) {
    //         $authors = [$authors];
    //     }

    //     $owners = $this->qb->table('authors')
    //         ->select($this->qb->raw('`id` AS `author`'))
    //         ->select($this->qb->raw('`owner` AS `user`'))
    //         ->whereIn('id', $authors);

    //     return $this->qb->table('users_authors')->alias('ua')
    //         ->select($this->qb->raw('`author_id` AS `author`'))
    //         ->select($this->qb->raw('`user_id` AS `user`'))
    //         ->whereIn('author_id', $authors)
    //         ->where('role', '>', 200)
    //         ->union($owners)
    //         ->get();
    // }
}
