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
        $data = $this->makeData($id, $author_id);       
        $data['title'] = __('Incoming message');

        $this->modelMessage->changeStatus($id, $author_id, MsgStatus::Read->value);
        $this->session->set('to', [$data['msg']->from]);
        $modelUserGroup->addToUserGroup(
            $this->user->id, $data['msg']->from, 
            MemberRole::Addressbook->value);
        
        $data['controls'] = 'message/controls_in.twig';

        return view('message/message', $data);
    }

    public function showOut($id)
    {
        $data = $this->makeData($id);
        
        if (isset($this->request->getQueryParams()['delete'])) {
            $data['alert'] = true;
        } else {
            $data['alert'] = false;
        }
       
        $data['title'] = __('Outgoing message');
        $data['controls'] = 'message/controls_out.twig';

        return view('message/message', $data);
    }

    public function showDel($id)
    {
        $data = $this->makeData($id);

        $data['title'] = __('Message to delete');
        $data['controls'] = 'message/controls_del.twig';

        return view('message/message', $data);
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

        return view('message/recipients', $data);
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

    private function makeData($id, $author_id = null)
    {
        $data['msg'] = $this->modelMessage->find($id);        
        $data['msg']->data = json_decode($data['msg']->data, true);
        $data['recipients'] = $this->modelMessage->getRecipients($id, $author_id);

        if ($author_id) {
            $data['to'] = $this->authorRepo->findAuthor($author_id);
            $to = $data['to']->alias;
        } else {
            $to = __('Author');
        }
        
        $data['msg']->data['body'] = nl2br(str_replace('{AUTHOR}', $to, $data['msg']->data['body']));

        $handler = $data['msg']->handler;
        
        $data['action'] = __FUNCTION__;
        $data['body'] = ($handler)
        ? container()->call([$handler, 'render'], ['data' => $data])
        : view('message/blank/default', $data);

        return $data;
    }
}
