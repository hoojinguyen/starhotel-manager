<?php

use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use Slim\Middleware\JwtAuthentication;
use Romi\BusinessLogic\LogicFactory;
use Romi\Middleware\OptionalAuth;
use Romi\Provider\AuthServiceProvider;
use Doctrine\ORM\EntityManager;
use Romi\Domain\Tenant;
use Slim\Http\Request;
use Slim\Http\Response;
use Romi\Shared\TenantScopePerRequest;


// DIC configuration

$container = $app->getContainer();

// Doctrine
require __DIR__ . '/doctrine.php';

// App Providers
$container->register(new AuthServiceProvider());

// view renderer
$container['renderer'] = function ($c) {
	$settings = $c->get('settings')['renderer'];
	return new Slim\Views\PhpRenderer($settings['template_path']);
};



// Register component on container
$container['view'] = function ($c) {
	$settings = $c->get('settings')['viewer'];
	$view = new Slim\Views\Twig($settings['template_path'], [
		'cache' => $settings['template_cache']
	]);

	// Instantiate and add Slim specific extension
	$router = $c->get('router');
	$uri = Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
	$view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

	return $view;
};

// monolog
$container['logger'] = function ($c) {
	$settings = $c->get('settings')['logger'];
	$logger = new Monolog\Logger($settings['name']);
	$logger->pushProcessor(new Monolog\Processor\UidProcessor());
	$logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
	return $logger;
};

// Fractal
$container['fractal'] = function ($c) {
	$manager = new Manager();
	$manager->setSerializer(new ArraySerializer());
	return $manager;
};

//Business Logic factory
$container['logic'] = function ($container) {
	$logicFactory = new LogicFactory($container);
	return $logicFactory;
};

// Request Validator
$container['validator'] = function ($c) {
	\Respect\Validation\Validator::with('Romi\\Validation\\Rules');
	return new Romi\Validation\Validator();
};

// Authorization Middlewares
$container['jwt'] = function ($c) {
	$jws_settings = $c->get('settings')['jwt'];

	$jws_settings['callback'] = function (Request $request, Response $response, $arguments)  use ($c) {
		if ($token = $arguments['decoded']) {
			$tenant = $c->get(EntityManager::class)
				->getRepository(Tenant::class)
				->findOneBy(['id' => $token->tenant]);
			if ($tenant) {
				//Apply TenantFilter
				$filter = $c->get(EntityManager::class)
					->getFilters()
					->enable('TenantFilter');
				$filter->setParameter('TenantId', $tenant->getId());
				//Used in Subscriber
				TenantScopePerRequest::applyTenantScope($tenant->getId());
			}
		}
	};

	return new JwtAuthentication($jws_settings);
};

$container['optionalAuth'] = function ($c) {
	return new OptionalAuth($c);
};

// Mailer
$container['mailer'] = function ($container) {
	$twig = $container['view'];
	$mailer = new \Anddye\Mailer\Mailer($twig, [
		'host'      => 'smtp.gmail.com',  // SMTP Host
		'port'      => '587' ,  // SMTP Port
		'username'  => '16520456@gm.uit.edu.vn',  // SMTP Username
		'password'  => 'nguyenvanhoi98',  // SMTP Password
		'protocol'  => 'tls'   // SSL or TLS
	]);

	// Set the details of the default sender
	$mailer->setDefaultFrom('16520456@gm.uit.edu.vn', 'StarHotel');

	return $mailer;
};