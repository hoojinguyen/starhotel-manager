<?php

namespace Romi\BusinessLogic;

use Interop\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;

class BaseLogic {

	private $em;
	private $logic;
	protected $fractal;
	protected $logger;

	public function __construct(ContainerInterface $container) {
		$this->em = $container->get(EntityManager::class);
		$this->logic = $container->get('logic');
		$this->fractal = $container->get('fractal');
		$this->logger = $container->get('logger');
				
	}

	public function getEntityManager(): EntityManager {
		return $this->em;
	}

	public function getLogic($name) {
		return $this->logic->findLogic($name);
	}

	public function saveOrUpdate($entity) {

		$this->em->persist($entity);
		$this->em->flush();	
		
	}

	public function delete($entity) {

		$this->em->remove($entity);
		$this->em->flush();	
		
	}

}
