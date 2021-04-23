<?php

namespace Romi\Provider;

use Interop\Container\ContainerInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Doctrine\ORM\EntityManager;
use Romi\Middleware\Auth;

class AuthServiceProvider implements ServiceProviderInterface {

	public function register(Container $container) {
		$container['auth'] = function (ContainerInterface $c) {
			return new Auth($c->get(EntityManager::class), $c->get('settings'));
		};
	}

}
