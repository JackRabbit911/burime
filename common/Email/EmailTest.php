<?php declare(strict_types=1);

namespace Common\Email;

use Sys\Controller\WebController;
use App\Email\Email;

class EmailTest extends WebController
{
    public function __invoke()
    {
        (new Email)->to('alx@buri.me', 'Алексей')
            ->tpl('default')->send();
        
        return 'Ok';
    }
}
