<?php
namespace Romi\Repository;

use Doctrine\ORM\EntityRepository;

class ContactRepository extends EntityRepository{

    public function anyMethodToFindData(){
        return 'data from repository';
    }
    
}