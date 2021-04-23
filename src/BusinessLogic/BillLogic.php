<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Bill;
use Romi\Domain\Discount;
use Romi\Domain\Tenant;
class BillLogic extends BaseLogic
{

  
    // create bill
    public function createBill($paramBill) {	
		$bill = $this->createBillEntity($paramBill);
		$this->saveOrUpdate($bill);
		return $bill;
	}

	protected function createBillEntity($paramBill){
		$bill = new Bill();		
		$this->setBillEntity($paramBill, $bill);
		return $bill;
	}

	public function setBillEntity($paramBill, Bill &$bill){

        $createdAt = new \DateTime($paramBill['createdAt']);
        $updatedAt = new \DateTime($paramBill['updatedAt']);

        $tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $paramBill['idTenant']));
        $discountId = $this->getEntityManager()->getRepository(Discount::class)->findOneBy(array('id' => $paramBill['idDiscount']));
        
		$bill->setAmount($paramBill['amount']);
        $bill->setDeposited($paramBill['deposited']);
        $bill->setPriceDiscount($paramBill['priceDiscount']);
        
        $bill->setTenantId($tenantId);
        $bill->setDiscountId($discountId);

        $bill->setCreatedBy($paramBill['createdBy']);
        $bill->setUpdatedBy($paramBill['updatedBy']);
        $bill->setCreatedAt($createdAt); 
        $bill->setUpdatedAt($updatedAt);
    }

    public function changeBill($amount,$billId){
        $change = $this->getEntityManager()->getRepository(Bill::class)->findOneBy(array('id' => $billId));
        if ($change) {
          $change->setAmount($amount);
          $this->getEntityManager()->flush();
          return $change;
        }
        return false;
    }

}
