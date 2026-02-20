<?php

declare(strict_types=1);

namespace App\Api\Auth\Service;

use Common\Email\Email;
use Psr\Http\Message\UriInterface;
use Sys\Template\TemplateInterface;

class SendEmail
{
    public function __construct(private TemplateInterface $tpl){}

    public function recovery(UriInterface $uri, object $user, string $lang, string $code)
    {
        $origin = $uri->getScheme() . '://' . $uri->getHost();

        $data = [
            'lang' => $lang,
            'title' => __('Burime'),
            'appeal' => __('Dear,') . ' ' . $user->name,
            'msg' => __('msg_restore'),
            'link_href' => $origin . ':5173/auth/recovery/password/' . $code,
            'link_title' => __('this link') 
        ];

        $html = $this->tpl->render('email/message', $data);

        (new Email)->to($user)
            ->mailbox(env('MAIL_BOX_ALX'))
            ->subject(__('Restore password'))
            ->body($html)
            ->send();
    }
}
