<?php

namespace Romi\BusinessLogic;

use Romi\Domain\PaymentRef;
use Romi\Domain\Tenant;
use Romi\Domain\Payment;

class PaymentRefLogic extends BaseLogic
{

    // create paymentRef
    public function createPaymentRef($paramPaymentRef) {	
		$paymentRef = $this->createPaymentRefEntity($paramPaymentRef);
		$this->saveOrUpdate($paymentRef);
		return $paymentRef;
	}

	protected function createPaymentRefEntity($paramPaymentRef){
		$paymentRef = new PaymentRef();		
		$this->setPaymentRefEntity($paramPaymentRef, $paymentRef);
		return $paymentRef;
	}

	public function setPaymentRefEntity($paramPaymentRef, PaymentRef &$paymentRef){


        $tenantId = $this->getEntityManager()->getRepository(Tenant::class)->findOneBy(array('id' => $paramPaymentRef['idTenant']));
        $paymentId = $this->getEntityManager()->getRepository(Payment::class)->findOneBy(array('id' => $paramPaymentRef['idPayment']));
        
        $paymentRef->setAmount($paramPaymentRef['amount']);
        $paymentRef->setYear($paramPaymentRef['year']);
        $paymentRef->setMonth($paramPaymentRef['month']);
        $paymentRef->setQuarter($paramPaymentRef['quarter']);

        $paymentRef->setTenantId($tenantId);
        $paymentRef->setPaymentId($paymentId);

    }

}
