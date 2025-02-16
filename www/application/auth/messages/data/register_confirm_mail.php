<?php

return [
    'email' => $userdata['email'],
    'name' => $userdata['name'],
    'subject' => 'Registration confirmation',
    'appeal' => "Dear, {$userdata['name']}!",
    // 'text' => url('register', ['action' => 'confirm', 'code' => $this->session->code]),
    'data' => ['html' => 'To complete the registration on this website click <a href="'
                . url('register', ['action' => 'confirmSave', 'code' => $this->session->code]) 
                . '">here</a>'],
];
