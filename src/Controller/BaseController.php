<?php

namespace Romi\Controller;

use Interop\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Romi\Middleware\Auth;
use Slim\Http\Request;
use Slim\Http\Response;


class BaseController {

	private $em;
	private $logic;
	protected $auth;
	protected $validator;
	protected $fractal;
	protected $logger;
	protected $view;

	public function __construct(ContainerInterface $container) {
		$this->em = $container->get(EntityManager::class);
		$this->logic = $container->get('logic');
		$this->auth = $container->get('auth');
		$this->validator = $container->get('validator');
		$this->fractal = $container->get('fractal');
		$this->logger = $container->get('logger');
		$this->view = $container->get('view');
	}

	public function getEntityManager(): EntityManager {
		return $this->em;
	}

	public function getLogic($name) {
		return $this->logic->findLogic($name);
	}
	
	public function getTenantRequest(Request $request){
		if ($requestTentant = $this->auth->requestTenant($request)){
			return $requestTentant;
		}
		return false;
	}
	
	public function tenantIdRequest(Request $request){
		if ($requestTentant = $this->getTenantRequest($request)){
			return $requestTentant->id;
		}
		return false;
	}

	public function formatMoneyVND($priceFloat)
	{
		$symbol = ' VNĐ';
		$symbol_thousand = ',';
		$decimal_place = 0;
		$price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
		return $price . $symbol;
	}
}
