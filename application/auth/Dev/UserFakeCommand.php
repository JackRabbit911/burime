<?php declare(strict_types=1);

namespace Auth\Dev;

use Sys\Console\Command;
use Sys\Console\CallApi;
use Sys\Fake\ModelTable;

class UserFakeCommand extends Command
{
    public function configure()
    {
        $this->addArgument('count', 'count of the records', 1);
        $this->addArgument('lang', 'language to generate fake data', 'en');
        $this->addOption('seed', 'insert to database if true, else - show');
        $this->addOption(['dump', 'd'], 'dump one record');
    }

    public function execute($count, $lang)
    {
        $generator = new UserFakeGenerator($lang);

        for ($i = 0; $i < $count; $i++) {
            $data[] = $generator->generate();
        }

        if ($this->input->opts['seed']) {
            $insertData = new CallApi(ModelTable::class, 'insert', ['table' => 'users']);
            $uids = $insertData->execute(['data' => $data]);
            
            foreach ($uids as $uid) {
                $fn[] = $generator->avatar($uid);
            }

            $this->climate->out(count($data) . 'records inserted into `users` table');
        } else {
            if ($this->input->opts['dump']) {
                $this->climate->dump($data[0]);
            } else {
                $this->climate->table($data);
            }
        }

        exit;
    }
}
