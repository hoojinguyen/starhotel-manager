<?php
namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class CustomerRepository extends EntityRepository{

    public function anyMethodToFindData(){
        return 'data from repository';
    }
    
}