<?php

use Sys\Response\ResponseHeader;

define('SYSPATH', DOCROOT . '../../system/');
define('FRAMEWORK', SYSPATH . 'vendor/alpha-zeta/framework/src/');
define('CONFIG', APPPATH . 'common/config/');
define('ENVPATH', DOCROOT . '../');
define('ROOTPATH', DOCROOT . '../../');
define('STORAGE', DOCROOT . '../storage/');

require_once SYSPATH . 'vendor/autoload.php';
require_once FRAMEWORK . 'autoload.php';
require_once FRAMEWORK . 'library.php';

if (is_file(FRAMEWORK . 'polyfill.php')) {
    require_once FRAMEWORK . 'polyfill.php';
}

date_default_timezone_set(env('APP_TZ'));

define('PRODUCTION', 10);
define('STAGE', 20);
define('TESTING', 30);
define('DEVELOPMENT', 40);

define('ENV', env('APP_ENV'));

define('DISPLAY_ERRORS', (ENV >= TESTING) ? true : false);

define('IS_DEBUG', false);
define('IS_CACHE', false);
define('STRICT_MODE', false);

define('MODE', getMode($_SERVER['REQUEST_URI']));

$GLOBALS['_MODE'] = MODE;

function getMode(string $path)
    {
        if (PHP_SAPI === 'cli') {
            return 'cli';
        } elseif (strpos($path, '/api/') === 0) {
            return 'api';
        } else {
            return 'web';
        }
    }

function getRoutePaths(): array
{
    return [
        CONFIG . 'routes/' . $GLOBALS['_MODE'] . '.php',
        APPPATH . 'auth/config/routes.php',
        CONFIG . 'routes/common.php',
    ];
}

if (ENV > PRODUCTION) {
    ResponseHeader::addHeader('X-Robots-Tag', 'noindex, nofollow, noimageindex');
}
