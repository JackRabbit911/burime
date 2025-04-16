<?php declare(strict_types=1);

namespace Common\Email;

use Sys\Mailer\Email as MailerEmail;

class Email extends MailerEmail
{
    private string $tpl_prefix = 'mail/templates/';

    public function to(mixed $to, string $name = ''): self
    {
        if (is_array($to)) {
            if (array_is_list($to)) {
                foreach ($to as $recipient) {
                    if (is_array($recipient)) {
                        $this->address($recipient[0], $recipient[1]);
                        $this->data['username'] = $recipient[1];
                    } elseif(is_object($recipient)) {
                        $this->address($recipient->email, $recipient->name);
                        $this->data['username'] = $recipient->name;
                    }
                }
            } else {
                $this->address($to['email'], $to['name']);
                $this->data['username'] = $to['name'];
            }
        } elseif(is_object($to)) {
            $this->address($to->email, $to->name);
            $this->data['username'] = $to->name;
        } elseif(is_string($to)) {
            $this->address($to, $name);
            $this->data['username'] = $name;
        }

        return $this;
    }

    public function mailbox($mailbox)
    {
        [$username, $password] = $mailbox;
        $this->username = $username;
        $this->password = $password;
    }

    public function tpl(string $tpl): self
    {
        $tpl = $this->tpl_prefix . $tpl;
        return parent::tpl($tpl);
    }
}
