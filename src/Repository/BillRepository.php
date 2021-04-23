<?php
namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class BillRepository extends EntityRepository{

    public function anyMethodToFindData(){
        return 'data from repository';
    }
    
}