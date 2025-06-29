<?php declare(strict_types = 1);

use Sys\AppFactory;

$GLOBALS['_start'] = hrtime(true);
$GLOBALS['_ram'] = memory_get_usage();

chdir(__DIR__);

define('DOCROOT', './');
require_once '../application/common/config/bootstrap.php';

AppFactory::create()->run();
