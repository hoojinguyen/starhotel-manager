<?php
namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class PrivilegesRepository extends EntityRepository{

    public function anyMethodToFindData(){
        return 'data from repository';
    }
    
}