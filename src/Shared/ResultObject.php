<?php

namespace Romi\Shared;

class ResultObject{
	
	private $result;
	private $name;
	private $data;
	
	public function __construct(bool $result, $name = 'data', $data = null) {
		$this->result = $result;
		$this->name = $name;
		$this->data = $data;
	}
	
	public function getResult() {
		return $this->result;
	}

	public function getName() {
		return $this->name;
	}

	public function getData() {
		return $this->data;
	}

}
