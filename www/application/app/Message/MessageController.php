<?php declare(strict_types=1);

namespace App\Message;

use App\Message\Model\ModelMessage;
use App\Message\Model\ModelRecipient;
use App\Message\MessageForm;
use App\Message\MessageValidation;
use App\Message\MsgStatus;
use Common\Contract\IModelUserGroup;
use Common\Contract\IModelGroup;
use Common\Repository\AuthorRepo;
use Common\Enum\MemberRole;
use Auth\Middleware\AuthGuardMiddleware;

use Sys\Controller\WebController;
use Az\Route\Route;

#[AuthGuardMiddleware]
class MessageController extends WebController
{
    private ModelMessage $modelMessage;
    private ModelRecipient $modelRecipient;
    private AuthorRepo $authorRepo;

    public function __construct(
        ModelMessage $model, 
        ModelRecipient $modelRecipient, 
        AuthorRepo $authorRepo
    ){
        $this->modelMessage = $model;
        $this->modelRecipient = $modelRecipient;
        $this->authorRepo = $authorRepo;
    }

    protected function _before()
    {
        $this->app->add('avatar', [$this->authorRepo, 'authorAvatar']);
    }

    public function list()
    {
        $ids = $this->modelMessage->getUsersGroupsIds($this->user->id);

        $data['inbox'] = $this->modelMessage->getInbox($this->user->id);
        $data['outbox'] = $this->modelMessage->getOutboxByIds($ids);
        $data['deleted'] = $this->modelMessage->getDeletedByIds($ids);
        $data['title'] = 'Messages list';

        return view('message/list', $data);
    }

    public function show(
        IModelUserGroup $modelUserGroup,
        $id, $author_id
    ){
        $data['title'] = __('Incoming message');
        $data['msg'] = $this->modelMessage->find($id);

        $data['msg']->data = json_decode($data['msg']->data, true);
        $data['recipients'] = $this->modelMessage->getRecipients($id, $author_id);
        $data['to'] = $this->authorRepo->findAuthor($author_id);

        $data['msg']->data['body'] = nl2br(str_replace('{AUTHOR}', $data['to']->alias, $data['msg']->data['body']));

        $this->modelMessage->changeStatus($id, $author_id, MsgStatus::Read->value);
        $this->session->set('to', [$data['msg']->from]);

        $modelUserGroup->addToUserGroup($this->user->id, $data['msg']->from, MemberRole::Addressbook->value);

        $handler = $data['msg']->handler;

        $data['action'] = __FUNCTION__;
        $data['body'] = ($handler)
            ? container()->call([$handler, 'render'], ['data' => $data])
            : view('web/message/blank/default', $data);

        return view('web/message/message', $data);
    }

    public function showOut($id)
    {
        $data['title'] = __('Outgoing message');
        $data['msg'] = $this->modelMessage->find($id);
        $data['msg']->data = json_decode($data['msg']->data, true);
        $data['recipients'] = $this->modelMessage->getRecipients($id);

        $data['msg']->data['body'] = nl2br(str_replace('{AUTHOR}', __('Author'), $data['msg']->data['body']));
        
        $handler = $data['msg']->handler;
        $data['action'] = __FUNCTION__;
        $data['body'] = ($handler)
            ? container()->call([$handler, 'render'], ['data' => $data])
            : view('web/message/blank/default', $data);

        if (isset($this->request->getQueryParams()['delete'])) {
            $data['alert'] = true;
        } else {
            $data['alert'] = false;
        }

        return view('web/message/message_out', $data);
    }

    public function showDel($id)
    {
        $data['title'] = __('Message to delete');
        $data['msg'] = $this->modelMessage->find($id);
        $data['msg']->data = json_decode($data['msg']->data, true);

        return view('web/message/message_del', $data);
    }

    public function form($author_id = null)
    {
        $new = $this->request->getQueryParams()['new'] ?? null;

        if ($new === 'true') {
            $this->session->remove('to');
            return $this->redirect(path('message', ['action' => 'recipients']));
        }

        $ids = $this->session->get('to');
        $recipients = ($ids) ? $this->modelRecipient->getByIds($ids) : [];

        return new MessageForm($this->user->ownAuthors, $recipients, $author_id);
    }

    public function recipients()
    {
        $queryParams = $this->request->getQueryParams();

        $filter = $queryParams['filter'] ?? null;
        $recipient = $queryParams['to'] ?? null;
        $remove = $queryParams['remove'] ?? null;

        if ($recipient) {
            $this->session->add('to', $recipient);
        }

        if ($remove) {
            $this->session->rm('to', $remove);
        }

        $recipients = $this->session->get('to') ?? [];
        $data['recipients'] = $this->modelRecipient->getByIds($recipients);
        $data['title'] = __('Recipients');
        $data['authors'] = $this->modelRecipient->getByFilter($filter, $recipients);

        return view('web/message/recipients', $data);
    }

    #[Route(methods: 'post')]
    #[MessageValidation]
    public function save(IModelUserGroup $modelUserGroup)
    {
        $this->session->delete('to');
        $data = $this->request->getParsedBody();

        $modelUserGroup->addToUserGroup($this->user->id, $data['to'], MemberRole::Addressbook->value);
        $id = $this->modelMessage->save($data);

        return $this->redirect(path('message', ['action' => 'showOut', 'id' => $id]));
    }

    public function delete($id, $author_id)
    {
        $this->modelMessage->delete($id, $author_id, $this->user->id);
        return $this->redirect(path('message', ['action' => 'list']));
    }

    public function forcedelete(IModelGroup $modelGroup, $id, $author_id)
    {
        if ($modelGroup->inGroup($this->user->id, $author_id)) {
            $this->modelMessage->forceDelete($id, $author_id);
        }

        return $this->redirect(path('message', ['action' => 'list']));
    }

    public function remove(IModelGroup $modelGroup, $id, $author_id)
    {
        if ($modelGroup->inGroup($this->user->id, $author_id)) {
            $this->modelMessage->remove($id);
        }

        return $this->redirect(path('message', ['action' => 'list']));
    }
}
