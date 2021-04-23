<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Service;
use Romi\Domain\ServiceType;
use Romi\Domain\Tenant;
use Romi\Shared\Cryptography;

class ServiceLogic extends BaseLogic
{


	public function loadService()
	{
		$services = $this->getEntityManager()->getRepository(Service::class)->createQueryBuilder('s')
			->select('distinct s.id,(s.serviceTypeId) as serviceId, st.active ,st.name as nameServiceType, st.active, s.name as nameService, s.price, s.status, s.unit, s.code')
			->leftJoin(ServiceType::class, 'st', 'WITH', 'st.id = s.serviceTypeId')
			->orderBy('s.serviceTypeId', 'ASC')
			->getQuery()
			->getResult();
		return $services;
	}

	public function loadInfoService($term)
	{
		return $this->getEntityManager()->getRepository(Service::class)->createQueryBuilder('s')
			->select('s.name, s.price,s.unit', 'st.name as type')
			->leftJoin(ServiceType::class, 'st', 'WITH', 'st.id=s.serviceTypeId')
			->where('s.status LIKE :term')
			->setParameter('term', '%' . $term . '%')
			->orderBy('st.name ', 'ASC')
			->getQuery()
			->getResult();
	}
	public function loadNameService()
	{

		$query = $this->getEntityManager()->createQuery('SELECT IDENTITY(s.serviceTypeId) as serviceId , s.unit, s.name, s.id, s.price FROM Romi\Domain\Service s');
		$res = $query->getResult();
		return $res;
	}


	// save and update
	public function createService($params)
	{
		$service = $this->createServiceEntity($params);
		$this->saveOrUpdate($service);
		return true;
	}

	protected function createServiceEntity($params)
	{
		$service = new Service();
		$this->setServiceEntity($params, $service);
		return $service;
	}

	public function setServiceEntity($params, Service &$service)
	{
		$tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => 1));
		$serviceTypeId = $this->getEntityManager()->getRepository(ServiceType::class)->findOneBy(array('id' => $params['serviceTypeId']));

		$service->setTenantId($tenantId);
		$service->setServiceTypeId($serviceTypeId);
		$service->setName($params['name']);
		$service->setPrice($params['price']);
		$service->setStatus($params['status']);
		$service->setUnit($params['unit']);
		$service->setCode($params['code']);
	}

	public function deleteService($id)
	{
		$service = $this->getEntityManager()
			->getRepository(Service::class)
			->findOneBy(array('id' => $id));
		if ($service) {
			$this->delete($service);
			return true;
		}
		return false;
	}


	// get info by id
	public function loadServiceById($id)
	{
		return $this->getEntityManager()->getRepository(Service::class)->createQueryBuilder('s')
			->select('distinct s.id, (s.serviceTypeId) as serviceTypeId, s.name , s.price, s.status, s.unit, s.code')
			->where('s.id = :id')
			->setParameter('id', $id)
			->getQuery()
			->getResult();
	}

	public function updateService($params)
	{
		$service = $this->getEntityManager()->getRepository(Service::class)->findOneBy(array('id' => $params['id']));
		if (!$service) {
			return false;
		}
		$this->setServiceEntity($params, $service);
		$this->getEntityManager()->flush();
		return true;
	}

	public function getServiceByTerm($term)
	{
		return $this->getEntityManager()
			->getRepository(Service::class)->createQueryBuilder('a')->where('a.name LIKE :term')
			->setParameter('term', '%' . $term . '%')
			->setMaxResults(20)
			->getQuery()
			->getResult();
	}
}
