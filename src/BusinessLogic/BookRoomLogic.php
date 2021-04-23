<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Booking;
use Romi\Domain\Tenant;
use Romi\Domain\Contact;
use Romi\Domain\BookingRoom;
use Romi\Domain\Room;
use Romi\Shared\Enum\BookRoomStatus;

class BookRoomLogic extends BaseLogic
{

    public function generateKey(){
        $keyLength = 8;
        $str = "1234567890QWERTYUIOPLKJHGFDSAZXCVBNM";
        $randStr= \substr(str_shuffle($str),0,$keyLength);
        return $randStr;

    }

    public function checkBookingCode($key){
         $check = $this->getEntityManager()->getRepository(Booking::class)->findOneBy(array('bookingCode' => $key));
         if($check!=null || $check != "" || $check){
             return false ;
         }
         return true;
    }

    public function createBookingCode(){
        $i=0;
        while ($i<1){
            $key = $this->generateKey();
            if($this->checkBookingCode($key)){
                return $key;
            }
            $i=0;
        }
    }

    public function	findRoomEmpty($chIn,$chOut,$roomType){

        if($roomType == 0){
            $dql = "SELECT r.id, r.name as roomName , rt.id as idRoomType , rt.name as roomType , rt.price as price 
            FROM Romi\Domain\Room r 
            LEFT JOIN Romi\Domain\RoomType rt WITH rt.id = r.roomTypeId 
            WHERE r.id NOT IN 
            (
                SELECT IDENTITY(br.roomId) FROM Romi\Domain\BookingRoom br 
                WHERE ( (br.dayCheckin <= :chIn AND br.dayCheckout> :chIn  ) OR (br.dayCheckout> :chIn  AND br.dayCheckin< :chOut) ) AND ( br.status != :cancel) AND ( br.status != :checkout)
            ) " ;
            $query = $this->getEntityManager()->createQuery($dql);
            $query->setParameter('chIn', $chIn);
            $query->setParameter('chOut', $chOut);
            $query->setParameter('cancel', BookRoomStatus::CANCEL);
            $query->setParameter('checkout', BookRoomStatus::CHECK_OUT);
            return $query->getResult();
        }
        else {
            $dql = "SELECT r.id, r.name as roomName , rt.id as idRoomType , rt.name as roomType , rt.price as price 
            FROM Romi\Domain\Room r 
            LEFT JOIN Romi\Domain\RoomType rt WITH rt.id = r.roomTypeId 
            WHERE r.id NOT IN 
            (
                SELECT IDENTITY(br.roomId) FROM Romi\Domain\BookingRoom br 
                WHERE (br.dayCheckin <= :chIn AND br.dayCheckout> :chIn  ) OR (br.dayCheckout> :chIn  AND br.dayCheckin< :chOut) AND ( br.status != :cancel) AND ( br.status != :checkout) 
            ) AND r.roomTypeId = :roomType " ;
            
            $query = $this->getEntityManager()->createQuery($dql);
            $query->setParameter('chIn', $chIn);
            $query->setParameter('chOut', $chOut);
            $query->setParameter('cancel', BookRoomStatus::CANCEL);
            $query->setParameter('checkout', BookRoomStatus::CHECK_OUT);
            $query->setParameter('roomType', $roomType);
            return $query->getResult();
        }
	
	}

    // create booking
    public function createBooking($paramBooking) {	
		$booking = $this->createBookingEntity($paramBooking);
		$this->saveOrUpdate($booking);
		return $booking;
	}

	protected function createBookingEntity($paramBooking){
		$booking = new Booking();		
		$this->setBookingEntity($paramBooking, $booking);
		return $booking;
	}

	public function setBookingEntity($paramBooking, Booking &$booking){

		$dayCheckin = new \DateTime($paramBooking['dayCheckin']);
		$dayCheckout = new \DateTime($paramBooking['dayCheckout']);
        $dayBooking = new \DateTime($paramBooking['dayBooking']);
        $createdAt = new \DateTime($paramBooking['createdAt']);
        $updatedAt = new \DateTime($paramBooking['updatedAt']);

        $tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $paramBooking['idTenant']));
        $contactId = $this->getEntityManager()->getRepository(Contact::class)->findOneBy(array('id' => $paramBooking['idContact']));
        
		$booking->setBookingCode($paramBooking['bookingCode']);
        $booking->setNote($paramBooking['note']);
        $booking->setAdult($paramBooking['adult']);
        $booking->setChild($paramBooking['child']);
        
		$booking->setDayCheckin($dayCheckin);
        $booking->setDayCheckout($dayCheckout);
        $booking->setDayBooking($dayBooking);
  
        
        $booking->setTenantId($tenantId);
        $booking->setContactId($contactId);
        $booking->setStatus($paramBooking['status']);

        $booking->setCreatedBy($paramBooking['createdBy']);
        $booking->setUpdatedBy($paramBooking['updatedBy']);
        $booking->setCreatedAt($createdAt); 
        $booking->setUpdatedAt($updatedAt);
    }


    // created booking room
    public function createBookingRoom($paramBooking,$idBooking) {	
		$booking = $this->createBookingRoomEntity($paramBooking,$idBooking);
		$this->saveOrUpdate($booking);
		return $booking;
	}

	protected function createBookingRoomEntity($paramBooking,$idBooking){
		$bookingRoom = new BookingRoom();		
		$this->setBookingRoomEntity($paramBooking,$idBooking, $bookingRoom);
		return $bookingRoom;
	}

	public function setBookingRoomEntity($paramBooking,$idBooking, BookingRoom &$bookingRoom){

		$dayCheckin = new \DateTime($paramBooking['dayCheckin']);
        $dayCheckout = new \DateTime($paramBooking['dayCheckout']);
        $timeArrival = new \DateTime($paramBooking['timeArrival']);
        $createdAt = new \DateTime($paramBooking['createdAt']);
        $updatedAt = new \DateTime($paramBooking['updatedAt']);

        $numGuest = $paramBooking['adult'] + $paramBooking['child'];

        $tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $paramBooking['idTenant']));
        $bookingId = $this->getEntityManager()->getRepository(Booking::class)->findOneBy(array('id' =>$idBooking ));
        $roomId = $this->getEntityManager()->getRepository(Room::class)->findOneBy(array('id' => $paramBooking['idRoom']));
    
        
        $bookingRoom->setGuestNumber($numGuest);
        $bookingRoom->setStatus($paramBooking['status']);
        
		$bookingRoom->setDayCheckin($dayCheckin);
        $bookingRoom->setDayCheckout($dayCheckout);
		$bookingRoom->setTimeArrival($timeArrival);

        $bookingRoom->setTenantId($tenantId);
        $bookingRoom->setBookingId($bookingId);
        $bookingRoom->setRoomId($roomId);

        $bookingRoom->setCreatedBy($paramBooking['createdBy']);
        $bookingRoom->setUpdatedBy($paramBooking['updatedBy']);
        $bookingRoom->setCreatedAt($createdAt); 
        $bookingRoom->setUpdatedAt($updatedAt);
    }
    

}
