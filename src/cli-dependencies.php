<?php

use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use Romi\BusinessLogic\LogicFactory;
use Doctrine\ORM\EntityManager;
use Slim\Http\Request;
use Slim\Http\Response;
use Romi\Shared\TenantScopePerRequest;
use Romi\Domain\Tenant;

// DIC configuration

$container = $app->getContainer();

// Doctrine
require __DIR__ . '/doctrine.php';

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
$container['logic'] = function ($c) {
	$logicFactory = new LogicFactory($c);
	return $logicFactory;
};

$container['errorHandler'] = function ($c) {
	return function ($request, $response, $exception) use ($c) {
             //this is wrong, i'm not with http
		return $c['response']->withStatus(500)
			->withHeader('Content-Type', 'text/text')
			->write('Something went wrong!');
	};
};

$container['notFoundHandler'] = function ($c) {
        //this is wrong, i'm not with http
	return function ($request, $response) use ($c) {
		return $c['response']
			->withStatus(404)
			->withHeader('Content-Type', 'text/text')
			->write('Not Found');
	};
};

//FIXME: call tenant GSI
$tenant = $container->get(EntityManager::class)
	->getRepository(Tenant::class)
	->findOneBy(['username' => 'gsi']);

if ($tenant) {
		//Apply TenantFilter
	$filter = $container->get(EntityManager::class)
		->getFilters()
		->enable('TenantFilter');

	$filter->setParameter('TenantId', $tenant->getId());
		//Used in Subscriber
	TenantScopePerRequest::applyTenantScope($tenant->getId());
};