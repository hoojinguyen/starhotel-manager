<?php

namespace Romi\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class ExistsWhenUpdate extends AbstractRule {

	private $repository;
	private $keyColumn;
	private $keyValue;
	private $columnName;

	public function __construct(EntityRepository $repository, $keyColumn, $keyValue, $columnName) {
		$this->repository = $repository;
		$this->keyColumn = $keyColumn;
		$this->keyValue = $keyValue;
		$this->columnName = $columnName;
	}

	public function validate($input) {
		$criteria = new Criteria();
		$criteria->where($criteria->expr()->eq($this->columnName, $input))
				->andWhere($criteria->expr()->neq($this->keyColumn, $this->keyValue));

		$count = $this->repository->matching($criteria)->count();
		return $count === 0;
	}

}
