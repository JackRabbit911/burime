<?php declare(strict_types=1);

namespace App\Message\Model;

use App\Message\Enum\MsgStatus;
use Common\Repository\AuthorRepo;
use Common\Contract\IModelUserGroup;
use Common\Enum\MemberRole;

class MsgRepo
{
    private ModelMessage $modelMessage;
    private ModelRecipient $modelRecipient;
    private AuthorRepo $authorRepo;
    private IModelUserGroup $modelUserGroup;

    public function __construct(
        ModelMessage $modelMessage,
        ModelRecipient $modelRecipient,
        AuthorRepo $authorRepo,
        IModelUserGroup $modelUserGroup
    ){
        $this->modelMessage = $modelMessage;
        $this->modelRecipient = $modelRecipient;
        $this->authorRepo = $authorRepo;
        $this->modelUserGroup = $modelUserGroup;
    }

    public function getList(array $ids): array
    {
        $data['inbox'] = $this->modelMessage->getInbox($ids);
        $data['outbox'] = $this->modelMessage->getOutbox($ids);
        $data['deleted'] = $this->modelMessage->getDeleted($ids);

        return $data;
    }

    public function getMsg($id)
    {
        $data['msg'] = $this->modelMessage->find($id);

        return $data;
    }

    public function getRecipients(array $ids): array
    {
        return $this->modelRecipient->getByIds($ids);
    }

    public function getAuthors($filter, $except): array
    {
        return $this->modelRecipient->getByFilter($filter, $except);
    }

    public function save($data)
    {
        $this->modelMessage->save($data);
    }

    public function authorAvatar($author_id, $alt = '')
    {
        return $this->authorRepo->authorAvatar($author_id, $alt);
    }

    public function makeData($id, $user_id, $author_id = null)
    {
        $data['msg'] = $this->modelMessage->find($id);        
        $data['msg']->data = json_decode($data['msg']->data, true);
        $data['recipients'] = $this->modelMessage->getRecipients($id, $author_id);

        if ($author_id) {
            $data['to'] = $this->authorRepo->findAuthor($author_id);
            $to = $data['to']->alias;
        } else {
            $to = 'Author';
        }
        
        $data['msg']->data['body'] = nl2br(str_replace('{AUTHOR}', $to, $data['msg']->data['body']));
        $data['msg']->view = $data['msg']->view ?: 'message/blank/default';
        
        $this->modelMessage->changeStatus($id, $author_id, MsgStatus::Read->value);

        $this->modelUserGroup->addToUserGroup(
            $user_id, $data['msg']->from, 
            MemberRole::Addressbook->value);

        return $data;
    }
}
