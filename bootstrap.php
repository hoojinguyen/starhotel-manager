<?php

use Slim\Container;

$container = new Container(require __DIR__ . '/src/settings.php');
require_once __DIR__ . '/src/doctrine.php';

return $container;
