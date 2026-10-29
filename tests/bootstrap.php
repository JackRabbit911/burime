<?php

// Bootstrap для PHPUnit-тестов приложения

define('DOCROOT', getcwd() . '/public/');
define('APPPATH', DOCROOT . '../application/');

// // Определяем корень releases/
// $releasesRoot = dirname(__DIR__, 3); // releases/

// // system/dev — здесь vendor, public, framework
// $systemDev = $releasesRoot . '/system/dev/';
// // application/dev — здесь app, auth, common, tests
// $appDev = $releasesRoot . '/application/dev/';

// define('DOCROOT', $systemDev . 'public/');
// define('APPPATH', $appDev);

require_once APPPATH . 'common/config/bootstrap.php';
