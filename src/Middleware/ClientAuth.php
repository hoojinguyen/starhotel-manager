<?php

namespace Romi\Middleware;

use Interop\Container\ContainerInterface;
use Slim\DeferredCallable;
use Doctrine\ORM\EntityManager;

class ClientAuth {

	private $container;
	private $em;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
		$this->em = $container->get(EntityManager::class);
	}

	public function __invoke($request, $response, $next) {

        $access = $request->getQueryParam('access');
        if( $access != 'true'){
            return $response->withRedirect('/client/homepage');
        }

		$response = $next($request,$response);
		return $response;
	}

}
