<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Service;
use Romi\Shared\Cryptography;
use Romi\Domain\ServiceDetail;
use Romi\Domain\Tenant;
use Romi\Domain\BookingRoom;

class ServiceDetailLogic extends BaseLogic
{

	public function checkExitServiceDetail($idService, $idBookingRoom) 
	{
		$check =  $this->getEntityManager()
		->getRepository(ServiceDetail::class)->createQueryBuilder('sd')
		->select('sd.id as idServiceDetail, sd.quantity, s.price, sd.amount')
		->leftJoin(Service::class, 's', 'WITH', 's.id=sd.serviceId')
		->where('sd.bookingRoomId = :idBookingRoom')
		->setParameter('idBookingRoom', $idBookingRoom)
		->andWhere('sd.serviceId = :idService')
		->setParameter('idService', $idService)
		->getQuery()
		->getResult();
		if($check){
			return $check;
		}
		return false;
		
	}

	public function createServiceDetail($paramServiceDetail)
    {
        $serviceDetail = $this->createServiceDetailEntity($paramServiceDetail);
        $this->saveOrUpdate($serviceDetail);
        return $serviceDetail;
    }

    protected function createServiceDetailEntity($paramServiceDetail)
    {
        $serviceDetail = new ServiceDetail();
        $this->setServiceDetailEntity($paramServiceDetail, $serviceDetail);
        return $serviceDetail;
    }

    public function setServiceDetailEntity($paramServiceDetail, ServiceDetail &$serviceDetail)
    {

        $createdAt = new \DateTime($paramServiceDetail['createdAt']);
        $updatedAt = new \DateTime($paramServiceDetail['updatedAt']);

        $tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $paramServiceDetail['idTenant']));
        $bookingRoomId = $this->getEntityManager()->getRepository(BookingRoom::class)->findOneBy(array('id' => $paramServiceDetail['idBookingRoom']));
        $serviceId = $this->getEntityManager()->getRepository(Service::class)->findOneBy(array('id' => $paramServiceDetail['idService']));

        $serviceDetail->setAmount($paramServiceDetail['amount']);
        $serviceDetail->setQuantity($paramServiceDetail['quantity']);

        $serviceDetail->setTenantId($tenantId);
        $serviceDetail->setBookingROomId($bookingRoomId);
        $serviceDetail->setServiceId($serviceId);

        $serviceDetail->setCreatedBy($paramServiceDetail['createdBy']);
        $serviceDetail->setUpdatedBy($paramServiceDetail['updatedBy']);
        $serviceDetail->setCreatedAt($createdAt); 
        $serviceDetail->setUpdatedAt($updatedAt);
    }

}
