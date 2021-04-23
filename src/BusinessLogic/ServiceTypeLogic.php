<?php

namespace Romi\BusinessLogic;

use Romi\Domain\ServiceType;
use Romi\Shared\Cryptography;
use Romi\Domain\Tenant;

class ServiceTypeLogic extends BaseLogic {

	
    public function loadServiceType()
	{
		$query = $this->getEntityManager()->createQuery('SELECT st.id, st.name, st.active , st.code FROM Romi\Domain\ServiceType st');
		$res = $query->getResult();
		return $res;
		
	}

	
	public function loadNameServiceType()
	{
		$query = $this->getEntityManager()->createQuery('SELECT st.id, st.name FROM Romi\Domain\ServiceType st');
		$res = $query->getResult();
		return $res;
		
	}

	// save and update
	public function createServiceType($params) {
			
		$serviceType = $this->createServiceTypeEntity($params);
		$this->saveOrUpdate($serviceType);
		return true;
	}

	protected function createServiceTypeEntity($params){
		$serviceType = new ServiceType();		
		$this->setServiceTypeEntity($params, $serviceType);
		return $serviceType;
	}

	public function setServiceTypeEntity($params, ServiceType &$serviceType){

		$tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => 1));

		$serviceType->setTenantId($tenantId);
        $serviceType->setName($params['name']);
        $serviceType->setCode($params['code']);
        $serviceType->setActive($params['active']);
	}
	
	


	// delete type service
	public function deleteServiceType($params){
		$serviceType = $this->getEntityManager()
		->getRepository(ServiceType::class)
		->findOneBy(array('id'=>$params['id']));
		if ($serviceType){
			$serviceType->setActive($params['active']);
			$this->getEntityManager()->flush();
			return true;
		}
			return false;
	}

	

	// get info by id
	public function loadServiceTypeById($idServiceType) {
		$serviceType = $this->getEntityManager()
		->getRepository(ServiceType::class)
		->findOneBy(array('id' => $idServiceType));
		return $serviceType;
	}

	//update 
	

	public function updateServiceType($params){
		$this->logger->info($params['id']);

		$serviceType = $this->getEntityManager()->getRepository(ServiceType::class)->findOneBy(array('id' => $params['id']));
		if (!$serviceType){

			return false;

		}
		$this->setServiceTypeEntity($params, $serviceType);
		
		$this->getEntityManager()->flush();
	
		return true;
	}


	


}
