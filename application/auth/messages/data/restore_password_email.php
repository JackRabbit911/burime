<?php

return [
    'subject' => 'Restore password',
    'appeal' => "Dear, {$user->name}!",
    'text' => url('restore', ['action' => 'confirm', 'code' => $this->session->code]),
    'data' => ['html' => 'To complete the registration on this website click <a href="'
                . url('restore', ['action' => 'confirm', 'code' => $this->session->code]) 
                . '">here</a>'],
];
