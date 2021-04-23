<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Config;

use Romi\Domain\Room;

use Romi\Domain\RoomDetails;
use Romi\Domain\Payment;
use Romi\Domain\PaymentRef;


use Romi\Repository\ConfigRepository;
use Romi\Domain\BookingRoom;
use Romi\Domain\Booking;
use Romi\Domain\RoomType;
use Romi\Domain\BillDetail;
use Romi\Domain\ServiceDetail;
use Romi\Domain\Service;
use Romi\Domain\Bill;
use Romi\Domain\Contact;
use Romi\Domain\Discount;
use Romi\Shared\Enum\BookRoomStatus;

define('NIGHT_CHECKIN_DEFAULT', 22);
define('CHECKIN_DEFAULT', 12);
define('CHECKOUT_DEFAULT', 12);
define('OT_MINUTE_MAX', 10);
define('HOUR_RENT', "Theo giờ");
define('DAY_RENT', "Theo ngày");
define('NIGHT_RENT', "Qua đêm");
define('SINGLE_ROOM', 1);
define('COUPLE_ROOM', 2);


class CheckoutLogic extends BaseLogic
{
	public function loadInfoDayBookingNewest($status,$currentDay){

		return $this->getEntityManager()->getRepository(BookingRoom::class)->createQueryBuilder('br')
		->select('distinct b.bookingCode , r.name as nameRoom,  rt.name as roomType , br.numGuest , b.dayBooking, c.name as nameContact')
		->leftJoin(Booking::class, 'b', 'WITH', 'b.id=br.bookingId')
		->leftJoin(Room::class, 'r', 'WITH', 'r.id=br.roomId')
		->leftJoin(RoomType::class, 'rt', 'WITH', 'rt.id =r.roomTypeId')
		->leftJoin(Contact::class, 'c', 'WITH', 'c.id=b.contactId')
		->where('br.status = :status')
		->setParameter('status', $status)
		->andWhere('b.dayBooking LIKE :currentDay')
		->setParameter('currentDay', '%' . $currentDay . '%')
		->orderBy('b.dayBooking', 'DESC')
		->getQuery()
		->getResult();


	}


	public function loadListRoom($status,$orderBy){
		$order = "";
		if($orderBy == BookRoomStatus::CHECK_IN ){
			$order = 'br.dayCheckin';
		}
		else if($orderBy == BookRoomStatus::CHECK_OUT) {
			$order = 'br.dayCheckout';
		}
		else {
			$order = 'b.dayBooking';
		}
		
		return $this->getEntityManager()->getRepository(BookingRoom::class)->createQueryBuilder('br')
		->select('distinct b.bookingCode , r.id as idRoom , b.id as idBill, r.name as nameRoom, rt.id as idRoomType ,  rt.name as roomType , bi.deposited , d.value as valueDiscount, br.id as idBookingRoom, br.numGuest ,br.dayCheckin, br.dayCheckout, br.timeArrival, b.dayBooking, c.name as nameContact')
		->leftJoin(Booking::class, 'b', 'WITH', 'b.id=br.bookingId')
		->leftJoin(Room::class, 'r', 'WITH', 'r.id=br.roomId')
		->leftJoin(RoomType::class, 'rt', 'WITH', 'rt.id =r.roomTypeId')
		->leftJoin(BillDetail::class, 'bd', 'WITH', 'br.id=bd.bookingRoomId')
		->leftJoin(Bill::class, 'bi', 'WITH', 'bi.id=bd.billId')
		->leftJoin(Discount::class, 'd', 'WITH', 'd.id=bi.discountId')
		->leftJoin(Contact::class, 'c', 'WITH', 'c.id=b.contactId')
		->where('br.status = :status')
		->setParameter('status', $status)
		->orderBy($order, 'ASC')
		->getQuery()
		->getResult();
	}



	public function getInfoCommon($codeBooking,$status){
		return $this->getEntityManager()->getRepository(Booking::class)->createQueryBuilder('b')
		->select('b.id as idBooking ,br.id as idBookingRoom, r.name as nameRoom, rt.name as roomType , br.numGuest, br.timeArrival, br.dayCheckin, br.dayCheckout, c.name as nameContact')
		->leftJoin(BookingRoom::class, 'br', 'WITH', 'b.id=br.bookingId')
		->leftJoin(Room::class, 'r', 'WITH', 'r.id=br.roomId')
		->leftJoin(RoomType::class, 'rt', 'WITH', 'rt.id=r.roomTypeId')
		->leftJoin(Contact::class, 'c', 'WITH', 'c.id=b.contactId')
		->where('b.bookingCode = :codeBooking')
		->setParameter('codeBooking', $codeBooking)
		->andWhere('br.status = :status')
		->setParameter('status', $status)
		->getQuery()
		->getResult();
	}

	public function getInfoFeeRoom($codeBooking){
		return $this->getEntityManager()->getRepository(Booking::class)->createQueryBuilder('b')
		->select('bi.id as idBill, bd.id as idBillDetail ,bd.typeFee, bd.feeName as nameFeeRoom , bd.feeAmount as priceFeeRoom , d.value as valueDiscount , bi.deposited, bi.priceDiscount')
		->leftJoin(BookingRoom::class, 'br', 'WITH', 'b.id=br.bookingId')
		->leftJoin(BillDetail::class, 'bd', 'WITH', 'br.id=bd.bookingRoomId')
		->leftJoin(Bill::class, 'bi', 'WITH', 'bi.id=bd.billId')
		->leftJoin(Discount::class, 'd', 'WITH', 'd.id=bi.discountId')
		->where('b.bookingCode = :codeBooking')
		->setParameter('codeBooking', $codeBooking)
		->getQuery()
		->getResult();
	}

	public function getInfoFeeService($codeBooking){
		$detailService = $this->getEntityManager()->getRepository(Booking::class)->createQueryBuilder('b')
		->select('sd.id as idServiceDetail, s.id as idService, s.name as nameService, s.price as priceService, s.unit, sd.quantity, sd.amount as amount')
		->leftJoin(BookingRoom::class, 'br', 'WITH', 'b.id=br.bookingId')
		->leftJoin(ServiceDetail::class, 'sd', 'WITH', 'br.id=sd.bookingRoomId')
		->leftJoin(Service::class, 's', 'WITH', 's.id=sd.serviceId')
		->where('b.bookingCode = :codeBooking')
		->setParameter('codeBooking', $codeBooking)
		->getQuery()
		->getResult();
		if($detailService[0]["nameService"] == null){
			return null;
		}
		else{
			return $detailService;
		} 
	
	}

	public function changeQuantityService($params)
	{
	  $change = $this->getEntityManager()->getRepository(ServiceDetail::class)->findOneBy(array('id' => $params["idServiceDetail"]));
	  if ($change) {
		$change->setQuantity($params["quantity"]);
		$change->setAmount($params["amount"]);
		$this->getEntityManager()->flush();
		return $change;
	  }
	  return false;
	}


	public function deleteFeeService($idDetailService)
	{
		$res = $this->getEntityManager()
			->getRepository(ServiceDetail::class)
			->findOneBy(array('id' => $idDetailService));
		if ($res) {
			$res = $this->delete($res);
			return true;
		}
		return false;
	}

	public function deleteFeeRoom($idBillDetail)
	{
		$res = $this->getEntityManager()
			->getRepository(BillDetail::class)
			->findOneBy(array('id' => $idBillDetail));
		if ($res) {
			$res = $this->delete($res);
			return true;
		}
		return false;
	}


	// public function account($orderId)
	// {
	// 	date_default_timezone_set('Asia/Bangkok');
	// 	$orderInfo = $this->getEntityManager()->getRepository(RoomDetails::class)->getOrderInfo($orderId);

	// 	if (!$orderInfo) {
	// 		return false;
	// 	}

	// 	switch ($orderInfo['rentType']) {
	// 		case HOUR_RENT: {
	// 				// Theo giờ
	// 				return $this->accountByHour($orderInfo);
	// 			}
	// 		case DAY_RENT: {
	// 				// Theo ngày
	// 				return $this->accountByDay($orderInfo);
	// 			}
	// 		case NIGHT_RENT: {
	// 				//Theo đêm
	// 				return $this->accountByNight($orderInfo);
	// 			}
	// 		default: {
	// 				break;
	// 			}
	// 	}
	// }

	// public function accountByDay($orderInfo)
	// {
	// 	$prices = $this->getDayPrice($orderInfo['roomType']);
	// 	$currentTime = new \Datetime(date('Y-m-d H:i'));
	// 	$checkinTime = $orderInfo['dateCheckin'];
	// 	$roomAmount = null;
	// 	$weekendDays = 0;
		
	// 	$rentDays = $currentTime->diff($checkinTime)->days;
	// 	if($currentTime->format('H') - $checkinTime->format('H') < 0){
	// 		$rentDays++;
	// 	}

	// 	if ($rentDays <= 1) {
	// 		$rentDays = 1;
	// 		if ($this->isWeekend($checkinTime)) {
	// 			$weekendDays = 1;
	// 			$roomAmount = $prices['weekendDayPrice'];
	// 		} else {
	// 			$weekendDays = 0;
	// 			$roomAmount = $prices['dayPrice'];
	// 		}
	// 		goto onlyOneDay;
	// 	}
	// 	$nextDay = new \Datetime($checkinTime->format('Y-m-d H:i'));;
	// 	for ($i = 1; $i <= $rentDays; $i++) {
	// 		if ($this->isWeekend($nextDay)) {
	// 			$roomAmount += $prices['weekendDayPrice'];
	// 			$weekendDays++;
	// 		} else {
	// 			$roomAmount += $prices['dayPrice'];
	// 		}
	// 		$nextDay->add(new \DateInterval('P1D'));
	// 	}
	// 	onlyOneDay: 
	// 	$OTHour = $currentTime->format('H') - CHECKOUT_DEFAULT;
	// 	if ($OTHour < 0) {
	// 		$OTHour = 0;
	// 	} else {
	// 		if ($currentTime->format('H') > OT_MINUTE_MAX) {
	// 			$OTHour += 1;
	// 		}
	// 	}

	// 	if ($OTHour > 5) {
	// 		if ($this->isWeekend($currentTime)) {
	// 			$weekendDays += 1;
	// 			$OTAmount = $prices['weekendDayPrice'];
	// 		} else {
	// 			$OTAmount = $prices['dayPrice'];
	// 		}
	// 	} else {
	// 		$OTAmount = $OTHour * $prices['OTHourPrice'];
	// 	}


	// 	$earlyHour = CHECKIN_DEFAULT - $checkinTime->format('H');
	// 	if ($earlyHour < 0) {
	// 		$earlyHour = 0;
	// 	}

	// 	$earlyAmount = $earlyHour * $prices['earlyHourPrice'];

	// 	$surcharge = ($orderInfo['numPeople'] - $prices['maxPeople']) * $prices['surcharge'];
	// 	if ($surcharge < 0) {
	// 		$surcharge = 0;
	// 	}

	// 	return array(
	// 		'checkinTime' => $checkinTime,
	// 		'checkoutTime' => $currentTime,
	// 		'rentDays' => $rentDays,
	// 		'weekendDays' => $weekendDays,
	// 		'OTHour' => $OTHour,
	// 		'OTAmount' => $OTAmount,
	// 		'earlyHour' => $earlyHour,
	// 		'earlyAmount' => $earlyAmount,
	// 		'roomAmount' => $roomAmount,
	// 		'total' => $roomAmount + $OTAmount + $surcharge,
	// 		'rentType' => DAY_RENT,
	// 		'surcharge' => $surcharge
	// 	);
	// }

	// public function accountByNight($orderInfo)
	// {
	// 	$prices = $this->getNightPrice($orderInfo['roomType']);
	// 	$currentTime = new \Datetime(date('Y-m-d H:i'));

	// 	$checkinTime = new \Datetime($orderInfo['dateCheckin']->format('Y-m-d H:i'));
	// 	$roomAmount = null;
	// 	$weekendDays = 0;

		
	// 	$rentDays = $currentTime->diff($checkinTime)->days;
	// 	if($currentTime->format('H') - $checkinTime->format('H') < 0){
	// 		$rentDays++;
	// 	}

	// 	if ($this->isWeekend($checkinTime)) {
	// 		$weekendDays = 1;
	// 		$roomAmount = $prices['weekendNightPrice'];
	// 	} else {
	// 		$weekendDays = 0;
	// 		$roomAmount = $prices['nightPrice'];
	// 	}

	// 	$earlyHour = NIGHT_CHECKIN_DEFAULT - $checkinTime->format('H');
	// 	if ($earlyHour < 0) {
	// 		$earlyHour = 0;
	// 	}
	// 	$earlyAmount = $earlyHour * $prices['earlyHourPrice'];

	// 	$quotation = array(
	// 		'checkinTime' => $checkinTime,
	// 		'checkoutTime' => $currentTime,
	// 		'rentDays' => $rentDays,
	// 		'weekendDays' => $weekendDays,
	// 		'earlyHour' => $earlyHour,
	// 		'earlyAmount' => $earlyAmount,
	// 		'roomAmount' => $roomAmount,
	// 		'total' => $roomAmount + $earlyAmount,
	// 		'rentType' => NIGHT_RENT,
	// 	);

	// 	if ($rentDays <= 1) {
	// 		$rentDays = 1;

	// 		$OTHour = $currentTime->format('H') - CHECKOUT_DEFAULT;
	// 		if ($OTHour < 0) {
	// 			$OTHour = 0;
	// 		} else {
	// 			if ($currentTime->format('H') > OT_MINUTE_MAX) {
	// 				$OTHour += 1;
	// 			}
	// 		}

	// 		if ($OTHour > 5) {

	// 			if ($this->isWeekend($currentTime)) {
	// 				$weekendDays += 1;
	// 				$quotation['OTAmount'] = $OTHour * $prices['weekendNightPrice'];
	// 			} else {
	// 				$quotation['OTAmount'] = $OTHour * $prices['nightPrice'];
	// 			}
	// 		} else {
	// 			$quotation['OTAmount'] = $OTHour * $prices['OTHourPrice'];
	// 		}

	// 		$quotation['OTHour'] = $OTHour;

	// 		$quotation['total'] += $quotation['OTAmount'];

	// 		$surcharge = ($orderInfo['numPeople'] - $prices['maxPeople']) * $prices['surcharge'];
	// 		if ($surcharge < 0) {
	// 			$surcharge = 0;
	// 		}

	// 		$quotation['surcharge'] = $surcharge;
	// 	} else {
	// 		$orderInfo['dateCheckin'] = $orderInfo['dateCheckin']->add(new \DateInterval('P1D'));
	// 		$quotation = $this->accountByDay($orderInfo);
	// 		$quotation['checkinTime'] = $checkinTime;
	// 		$quotation['total'] += $roomAmount + $earlyAmount;
	// 		$quotation['roomAmount'] += $roomAmount;

	// 		$quotation['earlyHour'] = $earlyHour;
	// 		$quotation['earlyAmount'] = $earlyAmount;
	// 		$quotation['rentType'] = NIGHT_RENT;
	// 		$quotation['rentDays'] = $rentDays;
	// 	}

	// 	return $quotation;
	// }

	// public function accountByHour($orderInfo)
	// {
	// 	$prices = $this->getHourPrice($orderInfo['roomType']);
	// 	$currentTime = new \Datetime(date('Y-m-d H:i'));
	// 	$checkinTime = $orderInfo['dateCheckin'];
	// 	$firstHour = $prices['firstHourAfter23h'];
	// 	$nextHour = $prices['nextHourAfter23h'];
	// 	$checkinHour = $checkinTime->format('H');
	// 	$checkinMinute = $checkinTime->format('i');
	// 	if($checkinMinute > 50) {
	// 		$checkinHour += 1;
	// 	}
	// 	$currentHour = $currentTime->format('H');
	// 	$currentMinute = $currentTime->format('i');
	// 	if($currentMinute > 10){
	// 		$currentHour += 1;
	// 	}
	// 	$timeInterval = $currentTime->diff($checkinTime);
	// 	if ($timeInterval->days >= 1){
	// 		if($checkinHour < 19){
	// 			return $this->accountByDay($orderInfo);
	// 		} else {
	// 			return $this->accountByNight($orderInfo);
	// 		}
	// 	}
	// 	$hours = $currentHour - $checkinHour;
	// 	if ($hours < 0) {
	// 		$hours += 24;
	// 	}
	// 	if ($checkinHour > 4 && $checkinHour < 23) {
	// 		$firstHour = $prices['firstHour'];
	// 		$nextHour = $prices['nextHour'];
	// 	}
	// 	$roomAmount = $firstHour + ($hours - 1) * $nextHour;



	// 	$surcharge = ($orderInfo['numPeople'] - $prices['maxPeople']) * $prices['surcharge'];
	// 	if ($surcharge < 0) {
	// 		$surcharge = 0;
	// 	}


	// 	return array(
	// 		'checkinTime' => $checkinTime,
	// 		'checkoutTime' => $currentTime,
	// 		'roomAmount' => $roomAmount,
	// 		'hours' => $hours,
	// 		'total' => $roomAmount + $surcharge,
	// 		'rentType' => HOUR_RENT,
	// 		'surcharge' => $surcharge
	// 	);
	// }

	// public function getHourPrice($roomType)
	// {
	// 	// tham số truyền vào sẽ là: có phải sau 11h hay không, kiểu phòng
	// 	$firstHour = 0;
	// 	$nextHour = 0;
	// 	$firstHourAfter23h = 0;
	// 	$nextHourAfter23h = 0;

	// 	$surcharge = 0; // phụ thu đầu người giống nhau nên lấy lúc đầu luôn
	// 	$maxPeople = 0;
	// 	$prices = $this->getEntityManager()->getRepository(Config::class)->getHourPrice($roomType);
	// 	switch ($roomType) {
	// 		case 1: {
	// 				foreach ($prices as $price) {
	// 					switch ($price['key']) {
	// 						case 'firstHourPrice1': {
	// 								$firstHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'nextHourPrice1': {
	// 								$nextHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'firstHourAfter23h1': {
	// 								$firstHourAfter23h = $price['value'];
	// 								break;
	// 							}
	// 						case 'nextHourAfter23h1': {
	// 								$nextHourAfter23h = $price['value'];
	// 								break;
	// 							}
	// 						case 'maxPeople1': {
	// 								$maxPeople = $price['value'];
	// 								break;
	// 							}
	// 						default: {
	// 								break;
	// 							}
	// 					}
	// 				}
	// 				break;
	// 			}
	// 		case 2: {
	// 				foreach ($prices as $price) {
	// 					switch ($price['key']) {
	// 						case 'firstHourPrice2': {
	// 								$firstHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'nextHourPrice2': {
	// 								$nextHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'firstHourAfter23h2': {
	// 								$firstHourAfter23h = $price['value'];
	// 								break;
	// 							}
	// 						case 'nextHourAfter23h2': {
	// 								$nextHourAfter23h = $price['value'];
	// 								break;
	// 							}
	// 						case 'maxPeople2': {
	// 								$maxPeople = $price['value'];
	// 								break;
	// 							}
	// 						default: {
	// 								break;
	// 							}
	// 					}
	// 				}
	// 				break;
	// 			}
	// 		default: {
	// 				return false;
	// 			}
	// 	}
	// 	$surcharge = (int)$this->getEntityManager()->getRepository(Config::class)->getSurcharge();

	// 	return array(
	// 		'firstHourAfter23h' => $firstHourAfter23h,
	// 		'nextHourAfter23h' => $nextHourAfter23h,
	// 		'firstHour' => $firstHour,
	// 		'nextHour' => $nextHour,
	// 		'maxPeople' => $maxPeople,
	// 		'surcharge' => $surcharge,
	// 	);
	// }

	// public function getNightPrice($roomType)
	// {
	// 	$OTHour = 0;
	// 	$earlyHour = 0;
	// 	$nightPrice = 0;
	// 	$dayPrice = 0;
	// 	$weekendDayPrice = 0;
	// 	$weekendNightPrice = 0;
	// 	$maxPeople = 0;
	// 	$prices = $this->getEntityManager()->getRepository(Config::class)->getNightPrice($roomType);
	// 	switch ($roomType) {
	// 		case 1: {
	// 				foreach ($prices as $price) {
	// 					switch ($price['key']) {
	// 						case 'nightPrice1': {
	// 								$nightPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'weekendNightPrice1': {
	// 								$weekendNightPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'dayPrice1': {
	// 								$dayPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'weekendDayPrice1': {
	// 								$weekendDayPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'OTHourPrice1': {
	// 								$OTHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'earlyHourPrice1': {
	// 								$earlyHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'maxPeople1': {
	// 								$maxPeople = $price['value'];
	// 								break;
	// 							}
	// 						default: {
	// 								break;
	// 							}
	// 					}
	// 				}
	// 				break;
	// 			}
	// 		case 2: {
	// 				foreach ($prices as $price) {
	// 					switch ($price['key']) {
	// 						case 'nightPrice2': {
	// 								$nightPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'weekendNightPrice2': {
	// 								$weekendNightPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'dayPrice2': {
	// 								$dayPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'weekendDayPrice2': {
	// 								$weekendDayPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'OTHourPrice2': {
	// 								$OTHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'earlyHourPrice2': {
	// 								$earlyHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'maxPeople2': {
	// 								$maxPeople = $price['value'];
	// 								break;
	// 							}
	// 						default: {
	// 								break;
	// 							}
	// 					}
	// 				}
	// 				break;
	// 			}
	// 		default: {
	// 				return false;
	// 			}
	// 	}

	// 	$surcharge = (int)$this->getEntityManager()->getRepository(Config::class)->getSurcharge(); // phụ thu đầu người giống nhau nên lấy lúc đầu luôn

	// 	return array(
	// 		'nightPrice' => $nightPrice,
	// 		'weekendNightPrice' => $weekendNightPrice,
	// 		'dayPrice' => $dayPrice,
	// 		'weekendDayPrice' => $weekendDayPrice,
	// 		'OTHourPrice' => $OTHour,
	// 		'earlyHourPrice' => $earlyHour,

	// 		'maxPeople' => $maxPeople,
	// 		'surcharge' => $surcharge,
	// 	);
	// }

	// public function getDayPrice($roomType)
	// {
	// 	$OTHour = 0;
	// 	$earlyHour = 0;
	// 	$dayPrice = 0;
	// 	$weekendDayPrice = 0;
	// 	$maxPeople = 0;
	// 	$prices = $this->getEntityManager()->getRepository(Config::class)->getDayPrice($roomType);
	// 	switch ($roomType) {
	// 		case 1: {
	// 				foreach ($prices as $price) {
	// 					switch ($price['key']) {
	// 						case 'dayPrice1': {
	// 								$dayPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'weekendDayPrice1': {
	// 								$weekendDayPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'OTHourPrice1': {
	// 								$OTHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'earlyHourPrice1': {
	// 								$earlyHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'maxPeople1': {
	// 								$maxPeople = $price['value'];
	// 								break;
	// 							}
	// 						default: {
	// 								break;
	// 							}
	// 					}
	// 				}
	// 				break;
	// 			}
	// 		case 2: {
	// 				foreach ($prices as $price) {
	// 					switch ($price['key']) {
	// 						case 'dayPrice2': {
	// 								$dayPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'weekendDayPrice2': {
	// 								$weekendDayPrice = $price['value'];
	// 								break;
	// 							}
	// 						case 'OTHourPrice2': {
	// 								$OTHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'earlyHourPrice2': {
	// 								$earlyHour = $price['value'];
	// 								break;
	// 							}
	// 						case 'maxPeople2': {
	// 								$maxPeople = $price['value'];
	// 								break;
	// 							}
	// 						default: {
	// 								break;
	// 							}
	// 					}
	// 				}
	// 				break;
	// 			}
	// 		default: {
	// 				return false;
	// 			}
	// 	}

	// 	$surcharge = (int)$this->getEntityManager()->getRepository(Config::class)->getSurcharge(); // phụ thu đầu người giống nhau nên lấy lúc đầu luôn

	// 	return array(
	// 		'dayPrice' => $dayPrice,
	// 		'OTHourPrice' => $OTHour,
	// 		'earlyHourPrice' => $earlyHour,
	// 		'weekendDayPrice' => $weekendDayPrice,
	// 		'maxPeople' => $maxPeople,
	// 		'surcharge' => $surcharge,
	// 	);
	// }

	// function isWeekend($date)
	// {
	// 	return $date->format('N') >= 6;
	// }

	// function payment($orderId, $amount){
	// 	$order = $this->getEntityManager()
	// 	->getRepository(RoomDetails::class)
	// 	->findOneBy(array('id' => $orderId));
	// 	$room = $this->getEntityManager()
	// 	->getRepository(Room::class)
	// 	->findOneBy(array('id' => $order->getRoomId()));
	// 	$order->setStatus('Đã thanh toán');
	// 	$room->setStatus('Còn trống');
	// 	$payment = new Payment();
	// 	$payment->setAmount($amount);
	// 	$payment->setOrderId($order);
	// 	$this->saveOrUpdate($payment);
	// 	$now = new \Datetime('now');
	// 	$paymentRef = new PaymentRef();
	// 	$paymentRef->setYear($now->format('Y'));
	// 	$paymentRef->setMonth($now->format('m'));
	// 	$paymentRef->setQuarter($this->getLogic('Report')->getQuarterFromDate($now));
	// 	$paymentRef->setAmount($amount);
	// 	$paymentRef->setPayemntId($payment);
	// 	$this->saveOrUpdate($paymentRef);

	// 	return true;
	// }
}
