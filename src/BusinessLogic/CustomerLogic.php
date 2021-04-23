<?php

namespace Romi\BusinessLogic;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Romi\Domain\Customer;

class CustomerLogic extends BaseLogic
{

	public function createCustomer($params)
	{

		$existCustomer = $this->getEntityManager()
			->getRepository(Customer::class)
			->findOneBy(array('id' => $params['id']));

		if ($existCustomer) {
			return false;
		} else {
			$name = $params['name'];
			$code = $params['code'];
			$active = $params['active'];

			$customer = new Customer();
			$customer->setName($name)
				->setCode($code)
				->setActive($active);

			$this->saveOrUpdate($customer);

			return true;
		}
	}

	public function updateCustomer($params)
	{
		$customer = $this->getEntityManager()
			->getRepository(Customer::class)
			->findOneBy(array('id' => $params['id']));

		if (!$customer) {
			return false;
		}

		$this->setCustomerEntity($params, $customer);
		$this->getEntityManager()->flush();

		return true;
	}

	protected function setCustomerEntity($params, Customer &$customer)
	{
		return $customer->setName($params['name'])
			->setActive($params['active'])
			->setCode($params['code']);
	}

	public function loadCustomer($customerId)
	{
		return $this->getEntityManager()
			->getRepository(Customer::class)
			->findOneBy(array('id' => $customerId));
	}

	public function loadCustomers($params){
		if ($params['sortDir'] == 1) {
			$sortDir = 'ASC';
		} else {
			$sortDir = 'DESC';
		}

		$criteria = array('name' => $params['searchBy']);
		$defaultPageSize = 10;
		$limit = $params['pageSize'] ? $params['pageSize'] : $defaultPageSize;
		$offset = $limit * $params['pageIndex'];

		$qb = $this->getEntityManager()
			->getRepository(Customer::class)
			->createQueryBuilder('a')
			->select('a');
		
		$query = $qb->orderBy('a.' . $params['sortBy'], $sortDir)
			->setMaxResults($limit)
			->setFirstResult($offset)->getQuery();
		$result = $query->getResult();
			
		$paginator = new Paginator($query, $fetchJoinCollection = true);

		$totalRows = count($paginator);
		return array('data' => $result,'totalRows' => $totalRows);
	}


	public function deleteCustomer($customerId)
	{
		$customer = $this->getEntityManager()
			->getRepository(Customer::class)
			->findOneBy(array('id' => $customerId));

		if ($customer) {
			$this->delete($customer);
			return true;
		} else {
			return false;
		}
	}

	public function countCustomer()
	{
		return $this->getEntityManager()
			->getRepository(Customer::class)
			->count();
	}
}
