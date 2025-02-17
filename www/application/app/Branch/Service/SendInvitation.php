<?php declare(strict_types=1);

namespace App\Branch\Service;

use Common\Contract\IFindBranch;
use Common\Contract\IModelMessage;
use Common\Enum\BranchAuthorStatus;

class SendInvitation
{
    private IModelMessage $modelMessage;
    private IFindBranch $modelBranch;

    public function __construct(IModelMessage $model, IFindBranch $modelBranch)
    {
        $this->modelMessage = $model;
        $this->modelBranch = $modelBranch;
    }

    public function send($branch, $to = null)
    {
        $data['from'] = $branch->master()->id;

        if (!$to) {
            $recipients = $branch->authors->where('status', '==', BranchAuthorStatus::Invited->value);
            $to = $recipients->props()->all();
        }
        
        $data['handler'] = __CLASS__;
        $data['to'] = $to;
        $data['subject'] = 'Приглашение к участию в проекте "' . $branch->title . '" в качестве соавтора';
        $data['data']['branch'] = $branch->id;
        $data['data']['body'] = <<<EOD
        Уважаемый {AUTHOR}!
        Приглашаю к участию в проекте: $branch->title.
        EOD;

        return $this->modelMessage->save($data);
    }

    public function render($data)
    {
        if (isset($data['to'])) {
            $params = [
                'branch_id' => $data['msg']->data['branch'],
                'author_id' => $data['to']->id,
            ];
    
            $params['action'] = 'accept';
            $vars['accept_link'] = path('participation', $params);
    
            $params['action'] = 'refuse';
            $vars['refuse_link'] = path('participation', $params);
        }

        $vars['branch'] = $this->modelBranch->findBranch($data['msg']->data['branch']);
        $vars['msg'] = $data['msg'];
        $vars['disabled'] = ($data['action'] === 'showOut') ? true : false;

        return view('web/message/blank/invitation', $vars);
    }
}
