<?php
namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class BookingRoomRepository extends EntityRepository{

    public function anyMethodToFindData(){
        return 'data from repository';
    }
    
}