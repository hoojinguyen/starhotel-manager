<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Payment;
use Romi\Domain\Tenant;
use Romi\Domain\Booking;
use Romi\Domain\BookingRoom;

class PaymentLogic extends BaseLogic
{

  
    // create payment
    public function createPayment($paramPayment) {	
		$payment = $this->createPaymentEntity($paramPayment);
		$this->saveOrUpdate($payment);
		return $payment;
	}

	protected function createPaymentEntity($paramPayment){
		$payment = new Payment();		
		$this->setPaymentEntity($paramPayment, $payment);
		return $payment;
	}

	public function setPaymentEntity($paramPayment, Payment &$payment){

        $createdAt = new \DateTime($paramPayment['createdAt']);
        $updatedAt = new \DateTime($paramPayment['updatedAt']);
        $paymentAt = new \DateTime($paramPayment['paymentAt']);

        $tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $paramPayment['idTenant']));
        $bookingId = $this->getEntityManager()->getRepository(Booking::class)->findOneBy(array('id' => $paramPayment['idBooking']));
        $bookingRoomId = $this->getEntityManager()->getRepository(BookingRoom::class)->findOneBy(array('id' => $paramPayment['idBookingRoom']));
        
        
		$payment->setAmount($paramPayment['amount']);
        $payment->setDescription($paramPayment['description']);
        $payment->setPaymentAt($paymentAt);
        
        $payment->setTenantId($tenantId);
        $payment->setBookingId($bookingId);
        $payment->setBookingRoomId($bookingRoomId);

        $payment->setCreatedBy($paramPayment['createdBy']);
        $payment->setUpdatedBy($paramPayment['updatedBy']);
        $payment->setCreatedAt($createdAt); 
        $payment->setUpdatedAt($updatedAt);
    }

}
