<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Slim\Container;
use Romi\Filter\TenantFilter;
use Doctrine\Common\EventManager;
use Romi\Shared\EventSubscribers\InsertTenancyEventSubscriber;

$container[EntityManager::class] = function (Container $container): EntityManager {
	$config = Setup::createAnnotationMetadataConfiguration(
			$container['settings']['doctrine']['metadata_dirs'], 
			$container['settings']['doctrine']['dev_mode']
	);

	$config->setMetadataDriverImpl(
			new AnnotationDriver(
			new AnnotationReader, $container['settings']['doctrine']['metadata_dirs']
			)
	);

	$config->setMetadataCacheImpl(
			new FilesystemCache(
			$container['settings']['doctrine']['cache_dir']
			)
	);
	
	$config->addFilter('TenantFilter', TenantFilter::class);
			
	$eventManager = new EventManager();
	$eventSubscriber = new InsertTenancyEventSubscriber();
	$eventManager->addEventSubscriber($eventSubscriber);

	return EntityManager::create($container['settings']['doctrine']['connection'], $config, $eventManager);
};

