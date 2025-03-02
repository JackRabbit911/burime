<?php declare(strict_types=1);

namespace App\Message\Controller;

use App\Message\Model\ModelMessage;
use App\Message\Model\MsgRepo;
use App\Message\Component\MessageForm;
use App\Message\Middleware\MessageValidation;
use App\Message\Msg;
use Common\Repository\AuthorRepo;
use Auth\Middleware\AuthGuardMiddleware;
use Sys\Controller\WebController;
use Az\Route\Route;
use HttpSoft\Response\RedirectResponse;

#[AuthGuardMiddleware]
class Message extends WebController
{
    private MsgRepo $repo;

    public function __construct(MsgRepo $repo)
    {
        $this->repo = $repo;
    }

    protected function _before()
    {
        $this->app->add('avatar', [$this->repo, 'authorAvatar']);
    }

    public function list()
    {
        $data = $this->repo->getList($this->user->ownAuthors->props()->all());
        $data['title'] = 'Messages list';

        return view('message/list', $data);
    }

    public function show($id, $author_id = null)
    {
        $data = $this->repo->makeData($id, $this->user->id, $author_id);
        $this->session->set('to', [$data['msg']->from]);
        $data['title'] = 'Incoming message';
        $data['controls'] = 'message/controls_in.twig';
        $data['msg']->disabled = false;

        return view('message/message', $data);
    }

    public function showOut($id)
    {
        $data = $this->repo->makeData($id, $this->user->id);
        
        if (isset($this->request->getQueryParams()['delete'])) {
            $data['alert'] = true;
        } else {
            $data['alert'] = false;
        }
       
        $data['title'] = __('Outgoing message');
        $data['controls'] = 'message/controls_out.twig';
        $data['msg']->disabled = true;

        return view('message/message', $data);
    }

    public function showDel($id)
    {
        $data = $this->repo->makeData($id, $this->user->id);

        $data['title'] = __('Message to delete');
        $data['controls'] = 'message/controls_del.twig';
        $data['msg']->disabled = true;

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
        $recipients = ($ids) ? $this->repo->getRecipients($ids) : [];

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

        $ids = $this->session->get('to') ?? [];
        $data['recipients'] = $this->repo->getRecipients($ids);
        $data['authors'] = $this->repo->getAuthors($filter, $ids);
        $data['title'] = 'Choice recipients';

        return view('message/recipients', $data);
    }

    #[Route(methods: 'post')]
    #[MessageValidation]
    public function save()
    {
        $data = $this->request->getParsedBody();
        Msg::fromArray($data)->save(ModelMessage::class);
 
        return new RedirectResponse(path('message', ['action' => 'list']));
    }
}
