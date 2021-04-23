<?php

namespace Romi\BusinessLogic;

use Interop\Container\ContainerInterface;

class LogicFactory {

	private $logicList = array();
	private $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	public function findLogic($name) {
		if (!in_array($name, $this->logicList)) {
			$logicClass = __NAMESPACE__ . '\\' . $name . 'Logic';
			$newLogic = new $logicClass($this->container);
			$this->logicList[$name] = $newLogic;
		}
		return $this->logicList[$name];
	}
}
