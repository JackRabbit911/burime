<?php

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

if (PHP_SAPI === 'cli') {
    define('MODE', 'cli');
} elseif (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
    define('MODE', 'api');
} else {
    define('MODE', 'web');
}

define('ROUTE_PATHS', [
    CONFIG . 'routes/' . MODE . '.php',
    APPPATH . 'auth/config/routes.php',
    CONFIG . 'routes/common.php',
]); 
