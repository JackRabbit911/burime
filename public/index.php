<?php declare(strict_types = 1);

$GLOBALS['_start'] = hrtime(true);
$GLOBALS['_ram'] = memory_get_usage();

chdir(__DIR__);

// echo 'It`s works'; exit;

define('MAINFOLDER', basename(__DIR__) . '/');
define('DOCROOT', './');
define('ROOTPATH', '../../');
define('SYSPATH', ROOTPATH . 'system/');
define('FRAMEWORK', SYSPATH . 'vendor/alpha-zeta/framework/src/');
define('APPPATH', ROOTPATH . MAINFOLDER . 'application/');
define('STORAGE', ROOTPATH . MAINFOLDER . 'storage/');
define('CONFIG', APPPATH . 'common/config/');
define('ENVPATH', ROOTPATH . MAINFOLDER);

require_once SYSPATH . 'vendor/autoload.php';
require_once FRAMEWORK . 'autoload.php';
require_once FRAMEWORK . 'library.php';
require_once CONFIG . 'bootstrap.php';

$container = (new Sys\ContainerFactory())->create(new DI\ContainerBuilder());
$app = $container->get(Sys\App::class);
$app->run();
