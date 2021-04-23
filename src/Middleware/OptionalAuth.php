<?php

namespace Romi\Middleware;

use Interop\Container\ContainerInterface;
use Slim\DeferredCallable;
use Doctrine\ORM\EntityManager;

class OptionalAuth {

	private $container;
	private $em;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
		$this->em = $container->get(EntityManager::class);
	}

	public function __invoke($request, $response, $next) {
		// if ($request->hasHeader('HTTP_AUTHORIZATION')) {
		// 	$callable = new DeferredCallable($this->container->get('jwt'), $this->container);
		// 	$response = call_user_func($callable, $request, $response, $next);
		// }
		// return $next($request, $response);

		if(! isset($_SESSION['user']) ){
			return $response->withRedirect('/admin/login');
		}

		$response = $next($request,$response);
		return $response;
	}

}
