<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Discount;


class DiscountLogic extends BaseLogic
{
    public function checkDiscount($codeDiscount){
        $res =  $this->getEntityManager()->getRepository(Discount::class)->createQueryBuilder('d')
        ->select('d.id, d.value')
        ->where('d.code = :codeDiscount')
        ->setParameter('codeDiscount', $codeDiscount)
        ->getQuery()
        ->getResult();
        return $res;
    } 
  

}
