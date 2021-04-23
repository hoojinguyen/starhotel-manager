<?php
namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class BillDetailRepository extends EntityRepository{

    public function anyMethodToFindData(){
        return 'data from repository';
    }
    
}