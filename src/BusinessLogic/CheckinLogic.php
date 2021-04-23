<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Booking;
use Romi\Domain\Contact;
use Romi\Domain\BookingRoom;
use Romi\Domain\Room;
use Romi\Domain\RoomType;
use Romi\Domain\GuestsInRoom;
use Romi\Domain\Tenant;
use Romi\Domain\Guest;

use Romi\Shared\Enum\BookRoomStatus;

class CheckinLogic extends BaseLogic
{


  public function getInfoBookRoom($bookingCode)
  {
    return $this->getEntityManager()->getRepository(Booking::class)->createQueryBuilder('b')
      ->select('br.id as bookingId,br.status,r.name as nameRoom ,rt.name as roomType, br.numGuest ,br.dayCheckin , br.dayCheckout  as dayCheckout, br.timeArrival , c.name as nameContact, c.phoneNumber, c.email, b.note')
      ->leftJoin(Contact::class, 'c', 'WITH', 'c.id=b.contactId')
      ->leftJoin(BookingRoom::class, 'br', 'WITH', 'b.id=br.bookingId')
      ->leftJoin(Room::class, 'r', 'WITH', 'r.id=br.roomId')
      ->leftJoin(RoomType::class, 'rt', 'WITH', 'rt.id=r.roomTypeId')
      ->where('b.bookingCode = :bookingCode')
      ->setParameter('bookingCode', $bookingCode)
      ->getQuery()
      ->getResult();
  }

  public function changeStatusBookingRoom($idBookingRoom,$st)
  {
    $status = $this->getEntityManager()->getRepository(BookingRoom::class)->findOneBy(array('id' => $idBookingRoom));
    if ($status) {
      $status->setStatus($st);
      $this->getEntityManager()->flush();
      return true;
    }
    return false;
  }

  
  public function changeRoom($idBookingRoom,$idRoomNew)
  {
    $change = $this->getEntityManager()->getRepository(BookingRoom::class)->findOneBy(array('id' => $idBookingRoom));
    if ($change) {
      $idRoom = $this->getEntityManager()->getRepository(Room::class)->findOneBy(array('id' =>$idRoomNew));
      $change->setRoomId($idRoom);
      $this->getEntityManager()->flush();
      return true;
    }
    return false;
  }


  public function getInfoGuestInRoom($idBookingRoom)
  {
    return $this->getEntityManager()
      ->getRepository(GuestsInRoom::class)->createQueryBuilder('gr')
      ->select('g.id , g.name, g.gender, g.yearOfBirth, g.idCardNo, g.phoneNumber, g.address')
      ->leftJoin(Guest::class, 'g', 'WITH', 'g.id=gr.guestId')
      ->where('gr.bookingRoomId = :idBookingRoom')
      ->setParameter('idBookingRoom', $idBookingRoom)
      ->getQuery()
      ->getResult();
  }

  // create guest in room
  public function createGuestInRoom($params)
  {
    $guestInRoom = $this->createGuestInRoomEntity($params);
    $this->saveOrUpdate($guestInRoom);
    return $guestInRoom;
  }

  protected function createGuestInRoomEntity($params)
  {
    $guestInRoom = new GuestsInRoom();
    $this->setGuestInRoomEntity($params, $guestInRoom);
    return $guestInRoom;
  }

  public function setGuestInRoomEntity($params, GuestsInRoom &$guestInRoom)
  {


    $createdAt = new \DateTime($params['createdAt']);
    $updatedAt = new \DateTime($params['updatedAt']);

    $tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $params['idTenant']));
    $bookingRoomId = $this->getEntityManager()->getRepository(BookingRoom::class)->findOneBy(array('id' => $params['idBookingRoom']));
    $guestId = $this->getEntityManager()->getRepository(Guest::class)->findOneBy(array('id' => $params['idGuest']));

    $guestInRoom->setTenantId($tenantId);
    $guestInRoom->setGuestId($guestId);
    $guestInRoom->setBookingRoomId($bookingRoomId);

    $guestInRoom->setCreatedBy($params['createdBy']);
    $guestInRoom->setUpdatedBy($params['updatedBy']);
    $guestInRoom->setCreatedAt($createdAt);
    $guestInRoom->setUpdatedAt($updatedAt);
  }

  public function checkExitsGuestInRoom($idGuest, $idBookingRoom)
  {
    $existGuest =  $this->getEntityManager()->getRepository(GuestsInRoom::class)->createQueryBuilder('gr')
      ->select('gr')
      ->where('gr.bookingRoomId = :idBookingRoom')
      ->setParameter('idBookingRoom', $idBookingRoom)
      ->andWhere('gr.guestId = :idGuest')
      ->setParameter('idGuest', $idGuest)
      ->getQuery()
      ->getResult();
    if (!$existGuest) {
      return true;
    }
    return false;
  }

}
