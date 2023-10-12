<?php

use app\App;
use app\Core\Autoloader;
use app\Core\Config;

error_reporting(0);
include 'Core/Autoloader.php';

$basePath = __DIR__.'/';
$autoloader = new Autoloader($basePath);
$autoloader->register();

$config = new Config($basePath.'config.php');

new App($config);