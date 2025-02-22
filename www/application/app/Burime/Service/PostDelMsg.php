<?php declare(strict_types=1);

namespace App\Burime\Service;

use Common\Contract\BranchInterface;
use App\Burime\Post;
use App\Burime\Model\FindBranch;
use App\Message\Model\ModelMessage;

class PostDelMsg
{
    private ModelMessage $modelMessage;
    private FindBranch $findBranch;

    public function __construct(ModelMessage $model, FindBranch $findBranch)
    {
        $this->modelMessage = $model;
        $this->findBranch = $findBranch;
    }

    public function send(BranchInterface $branch, Post $post, int $user_id)
    {
        $data['handler'] = __CLASS__;
        $data['from'] = $branch->authors->getInstance($user_id, 'user_id')->id;
        $data['to'] = $post->author_id;
        $data['subject'] = 'Пост удалён модератором';
        $data['data']['branch'] = $branch->id;
        $data['data']['body'] = $this->makeBody($post, 8);

        return $this->modelMessage->save($data);
    }

    public function render($data)
    {
        $vars['branch'] = $this->findBranch->find($data['msg']->data['branch']);
        $vars['msg'] = $data['msg'];

        return view('web/message/blank/post_deleted', $vars);
    }

    private function makeBody($post, $count_words)
    {
        $substr = $this->substr($post->body, $count_words);

        return <<<EOD
        Уважаемый {AUTHOR}! Ваш пост
        от $post->created
        "$substr..."
        был удалён модератором
        EOD;
    }

    private function substr($str, $count_words)
    {
        $array = explode(' ', $str, $count_words);
        unset($array[array_key_last($array)]);
        return implode(' ', $array);
    }
}
