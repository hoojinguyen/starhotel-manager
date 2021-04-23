<?php 
require __DIR__ . '/vendor/autoload.php';

// use Romi\BusinessLogic\ImportLogic;
use Slim\Http\Environment;

session_start();

$argv = $GLOBALS['argv'];
array_shift($argv);
$pathInfo = implode('/', $argv);
$env = Environment::mock(['REQUEST_URI' => '/' . $pathInfo]);

// Instantiate the app
$settings = require __DIR__ . '/src/settings.php';

$settings['environment'] = $env;

$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/src/cli-dependencies.php';

$app->get('/import', function () use ($container) {
	   // do import calling Actions or Controllers
	// $command = new ImportLogic($container);
	// $command->doImport();
});

// Run app
$app->run();