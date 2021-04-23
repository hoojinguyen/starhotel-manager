<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Order;
use Romi\Domain\Guest;
use Romi\Domain\Room;

class OrderLogic extends BaseLogic
{
	public function loadOrderrId()
	{
        $order = $this->getEntityManager()->getRepository(Order::class)->createQueryBuilder('r')
		->select('r')
		->leftJoin(Guest::class,'f','WITH','f.id=r.id')
		->leftJoin(Room::class,'rt','WITH','rt.id=r.id')
        ->getQuery()
        ->getResult()
        ;
        return $order;
	}


	// save and update
	public function createOrderRoom($paramOrder) {	
		$order = $this->createOrderRoomEntity($paramOrder);
		$this->saveOrUpdate($order);
		return $order;
	}

	protected function createOrderRoomEntity($paramOrder){
		$order = new Order();		
		$this->setOrderRoomEntity($paramOrder, $order);
		return $order;
	}

	public function setOrderRoomEntity($paramOrder, Order &$order){

		$dateCheckin = new \DateTime($paramOrder['dateCheckin']);
		$dateExpectCheckout = new \DateTime($paramOrder['dateExpectCheckout']);

		$guestId = $this->getEntityManager()->getRepository(Guest::class)->findOneBy(array('id' => $paramOrder['guestId']));
		$roomId = $this->getEntityManager()->getRepository(Room::class)->findOneBy(array('id' => $paramOrder['roomId']));
		$order->setGuestId($guestId);
		$order->setRoomId($roomId);
		$order->setRentType($paramOrder['rentType']);
		$order->setDateCheckin($dateCheckin);
        $order->setDateExpectCheckout($dateExpectCheckout);
		$order->setNumPeople($paramOrder['numPeople']);
		$order->setStatus($paramOrder['status']);
		
	}

	public function getOrderRoomId($id)
	{
		
		$info = $this->getEntityManager()->getRepository(Order::class)->findOneBy(array('id' => $id));
        // $info = $this->getEntityManager()->getRepository(Order::class)->createQueryBuilder('o')
		// ->select('o')
		// ->where('o.id = :id')
		// ->setParameter('id', $id)
        // ->getQuery()
        // ->getResult()
        // ;
        return $info;
	}

	public function fixStatusOrder($params){
		$order = $this->getEntityManager()->getRepository(Order::class)->findOneBy(array('id' => $params['id']));

		$dateCheckin = new \DateTime($params['dateCheckin']);
		
		if ($order){
			$order->setStatus($params['status']);
			$order->setDateCheckin($dateCheckin);
			$this->getEntityManager()->flush();
			return true;
		}
			return false;
	}




}
