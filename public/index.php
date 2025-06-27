<?php declare(strict_types = 1);

use Sys\AppFactory;

$GLOBALS['_start'] = hrtime(true);
$GLOBALS['_ram'] = memory_get_usage();

chdir(__DIR__);

define('SYSPATH', '../../system/');
define('FRAMEWORK', SYSPATH . 'vendor/alpha-zeta/framework/src/');
define('APPPATH', '../application/');
define('CONFIG', APPPATH . 'common/config/');
define('ENVPATH', '../');

require_once SYSPATH . 'vendor/autoload.php';
require_once FRAMEWORK . 'autoload.php';
require_once FRAMEWORK . 'library.php';
require_once CONFIG . 'bootstrap.php';

(AppFactory::create())->run();
