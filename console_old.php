#!/usr/bin/php
<?php

use Sys\AppFactory;

define('DOCROOT', './public/');
require_once 'application/common/config/bootstrap.php';

AppFactory::console()->run();
