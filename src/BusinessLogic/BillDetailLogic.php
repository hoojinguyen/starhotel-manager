<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Tenant;
use Romi\Domain\BillDetail;
use Romi\Domain\BookingRoom;
use Romi\Domain\Bill;


class BillDetailLogic extends BaseLogic
{

    public function createBillDetail($paramBillDetail)
    {
        $billDetail = $this->createBillDetailEntity($paramBillDetail);
        $this->saveOrUpdate($billDetail);
        return $billDetail;
    }

    protected function createBillDetailEntity($paramBillDetail)
    {
        $billDetail = new BillDetail();
        $this->setBillDetailEntity($paramBillDetail, $billDetail);
        return $billDetail;
    }

    public function setBillDetailEntity($paramBillDetail, BillDetail &$billDetail)
    {

        $createdAt = new \DateTime($paramBillDetail['createdAt']);
        $updatedAt = new \DateTime($paramBillDetail['updatedAt']);

        $tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $paramBillDetail['idTenant']));
        $bookingRoomId = $this->getEntityManager()->getRepository(BookingRoom::class)->findOneBy(array('id' => $paramBillDetail['idBookingRoom']));
        $billId = $this->getEntityManager()->getRepository(Bill::class)->findOneBy(array('id' => $paramBillDetail['idBill']));


        $billDetail->setDescription($paramBillDetail['description']);
        $billDetail->setFeeAmount($paramBillDetail['feeAmount']);
        $billDetail->setFeeName($paramBillDetail['feeName']);
        $billDetail->setTypeFee($paramBillDetail['typeFee']);

        $billDetail->setTenantId($tenantId);
        $billDetail->setBookingRoomId($bookingRoomId);
        $billDetail->setBillId($billId);

        $billDetail->setCreatedBy($paramBillDetail['createdBy']);
        $billDetail->setUpdatedBy($paramBillDetail['updatedBy']);
        $billDetail->setCreatedAt($createdAt); 
        $billDetail->setUpdatedAt($updatedAt);
    }


    
    public function sumAmountWhenChange($billId){
        return $this->getEntityManager()->getRepository(BillDetail::class)->createQueryBuilder('bd')
        ->select('SUM(bd.feeAmount)')
        ->where('bd.billId = :billId')
        ->setParameter('billId', $billId)
        ->getQuery()
        ->getResult();
    }

    public function changeBillDetail($billId,$date, $feeAmount, $updatedAt){
        $updatedAt = new \DateTime($updatedAt);
        $feeName = (string)$date;
        $change = $this->getEntityManager()->getRepository(BillDetail::class)->findOneBy(['billId' => $billId,'feeName' => $feeName]);
        if ($change) {
          $change->setFeeAmount($feeAmount);
          $change->setUpdatedAt($updatedAt);
          $this->getEntityManager()->flush();
          return $change;
        }
        return false;
    }
}
