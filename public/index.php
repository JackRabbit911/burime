<?php declare(strict_types = 1);

$GLOBALS['_start'] = hrtime(true);
$GLOBALS['_ram'] = memory_get_usage();

chdir(__DIR__);

// echo 'It`s works'; exit;

define('PUBPATH', __DIR__ . '/');
define('ROOTPATH', '../../');
define('MAINFOLDER', pathinfo(dirname(__DIR__))['filename'] . '/');
define('DOCROOT', '../../htdocs/' . MAINFOLDER);
define('SYSPATH', '../../system/');
define('FRAMEWORK', SYSPATH . 'vendor/alpha-zeta/framework/src/');
define('APPPATH', '../application/');
define('STORAGE', '../storage/');
define('CONFIG', APPPATH . 'common/config/');
define('ENVPATH', '../');

require_once SYSPATH . 'vendor/autoload.php';
require_once FRAMEWORK . 'autoload.php';
require_once FRAMEWORK . 'library.php';
require_once CONFIG . 'bootstrap.php';

$container = (new Sys\ContainerFactory())->create(new DI\ContainerBuilder());
$app = $container->get(Sys\App::class);
$app->run();
