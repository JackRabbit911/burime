<?php

declare(strict_types=1);

namespace Adm\Repository;

use Auth\Api\Model\ModelRefreshToken;
use Az\Session\SessionInterface;
use Sys\Console\Command\Clear\Log;
use Sys\CSRF\Facade\Csrf;

class ClearRepo
{
    public function __construct(
        private ModelRefreshToken $modelToken,
        private SessionInterface $session,
    ) {}

    public function start()
    {
        $logfile = STORAGE . 'logs/error.log';

        if (file_exists($logfile)) {
            $output = shell_exec('wc -l ' . escapeshellarg($logfile));
            $count = (int)$output;

            $result['logs'] = $count;
        } else {
            $result['logs'] = 0;
        }

        $result['sessions'] = $this->session->getExpired();

        $csrf = Csrf::getExpired();
        $refresh = $this->modelToken->getExpired();

        $result['tokens'] = $csrf + $refresh;


        return $result;
    }

    public function logs()
    {
        [$success, $error] = Log::clear();

        return [
            'cleared' => $success,
            'notFound' => $error,
        ];
    }

    public function sessions()
    {
        return ['sessions' => $this->session->gc()];
    }

    public function tokens()
    {
        $scrf = Csrf::gc();
        $tokens = $this->modelToken->gc();

        return ['tokens' => $scrf + $tokens];
    }

    public function all()
    {
        $result['logs'] = $this->logs();
        return array_merge($result, $this->sessions(), $this->tokens());
    }
}
