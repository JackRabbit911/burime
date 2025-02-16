<?php declare(strict_types=1);

use Sys\Console\Controller as ConsoleController;

return [
    'console' => ['/api/console/{model}/{method}', ConsoleController::class, ['model' => '[\w\/]+']],
];
