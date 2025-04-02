<?php declare(strict_types=1);

namespace App\Burime\Controller;

use Sys\Controller\WebController;
use HttpSoft\Response\HtmlResponse;

class DeletePostApi extends WebController
{
    public function confirm($post_id)
    {
        $data = [
            'post_id' => $post_id,

        ];

        $html = view('burime/confirm_del_post_dialog', $data);
        return new HtmlResponse($html);
    }
}
