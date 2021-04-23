<?php
namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class CheckinRepository extends EntityRepository{

    public function anyMethodToFindData(){
        return 'data from repository';
    }
    
}