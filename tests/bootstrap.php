<?php

error_reporting(-1);
date_default_timezone_set('UTC');

if (!file_exists(dirname(__DIR__) . '/composer.lock')) {
    die('Dependencies must be installed via composer first');
}

$loader = require dirname(__DIR__) . '/vendor/autoload.php';