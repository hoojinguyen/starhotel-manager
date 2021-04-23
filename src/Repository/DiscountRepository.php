<?php
namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class DiscountRepository extends EntityRepository{

    public function anyMethodToFindData(){
        return 'data from repository';
    }
    
}