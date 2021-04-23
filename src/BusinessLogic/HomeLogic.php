<?php

namespace Romi\BusinessLogic;


use Romi\Domain\Guest;
use Romi\Domain\Room;
use Romi\Domain\Employee;
use Romi\Shared\Cryptography;
use Romi\Domain\Booking;
use Romi\Domain\BookingRoom;
use Romi\Domain\GuestsInRoom;

class HomeLogic extends BaseLogic
{

  // public function loadNumEmptyRoom($term)
  // {
  //   return $this->getEntityManager()->getRepository(Room::class)->createQueryBuilder('r')
  //     ->select('COUNT(r.id) as numEmptyRoom')
  //     ->where('r.status LIKE :term')
  //     ->setParameter('term', '%' . $term . '%')
  //     ->getQuery()
  //     ->getResult();
  // }

  public function loadNumGuestBooking($term)
  {
    return $this->getEntityManager()->getRepository(BookingRoom::class)->createQueryBuilder('br')
      ->select(' SUM(br.numGuest) as numGuestOrder')
      ->where('br.status = :term')
      ->setParameter('term', $term)
      ->getQuery()
      ->getResult();
  }


  public function loadNumGuestBy($status)
  {
    return $this->getEntityManager()->getRepository(BookingRoom::class)->createQueryBuilder('br')
      ->select(' SUM(br.numGuest) as numGuest')
      ->andWhere('br.status = :status')
      ->setParameter('status', $status)
      ->getQuery()
      ->getResult();
  }


  public function loadInfoGuestBookRoom($term)
  {
    return $this->getEntityManager()->getRepository(Booking::class)->createQueryBuilder('b')
      ->select(' br.numGuest,br.dayCheckin ', 'br.dayCheckout ', 'r.name as nameRoom, g.name as nameGuest')
      ->leftJoin(BookingRoom::class, 'br', 'WITH', 'br.bookingId=b.id')
      ->leftJoin(GuestsInRoom::class, 'gr', 'WITH', 'gr.bookingRoomId=br.id')
      ->leftJoin(Guest::class, 'g', 'WITH', 'gr.guestId=g.id')
      ->leftJoin(Room::class, 'r', 'WITH', 'r.id=br.roomId')
      ->where('br.status = :term')
      ->setParameter('term', $term)
      ->getQuery()
      ->getResult();
  }


}
