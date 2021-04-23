<?php
namespace Romi\BusinessLogic;
use Romi\Domain\Price;
use Romi\Domain\Room;


class AccountingLogic extends BaseLogic {

	public function getPriceRoomType(){
		return 	$this->getEntityManager()->getRepository(Price::class)->getPriceRoomType();
	}


	public function accountByDay($roomId, \Datetime $checkinTime, \Datetime $checkoutTime)
	{
		$roomTypeId = $this->getEntityManager()->getRepository(Room::class)->getRoomTypeById($roomId);
		$prices = $this->PreparePriceResult($this->getEntityManager()->getRepository(Price::class)->getDayPrice($roomTypeId));

		$rentDays = $checkoutTime->diff($checkinTime)->days;

		if(strtotime($checkoutTime->format('H:i:s')) < strtotime($checkinTime->format('H:i:s'))){
			$rentDays++;
		}

		$roomAmount = 0;

		$result = [];

		// if ($rentDays <= 1) {
		// 	$rentDays = 1;
		// 	if ($this->isWeekend($checkinTime)) {
		// 		$weekendDays = 1;
		// 		$roomAmount = $prices['weekendDayPrice'];
		// 	} else {
		// 		$weekendDays = 0;
		// 		$roomAmount = $prices['dayPrice'];
		// 	}
		// 	goto onlyOneDay;
		// }
		$nextDay = new \Datetime($checkinTime->format('Y-m-d H:i'));

		for ($i = 0; $i < $rentDays; $i++) {
			if ($this->isWeekend($nextDay)) {
				array_push($result, [
					'weekday' => $nextDay->format('l'),
					'date' => $nextDay->format('d-m-Y'),
					'amount' => $prices['DAY_WEEKEND']
				]);
				$roomAmount += $prices['DAY_WEEKEND'];
			} else {
				array_push($result, [
					'weekday' => $nextDay->format('l'),
					'date' => $nextDay->format('d-m-Y'),
					'amount' => $prices['DAY_DEFAULT']
				]);
				$roomAmount += $prices['DAY_DEFAULT'];
			}
			$nextDay->add(new \DateInterval('P1D'));
		}
		// onlyOneDay: 
		// $OTHour = $currentTime->format('H') - CHECKOUT_DEFAULT;
		// if ($OTHour < 0) {
		// 	$OTHour = 0;
		// } else {
		// 	if ($currentTime->format('H') > OT_MINUTE_MAX) {
		// 		$OTHour += 1;
		// 	}
		// }

		// if ($OTHour > 5) {
		// 	if ($this->isWeekend($currentTime)) {
		// 		$weekendDays += 1;
		// 		$OTAmount = $prices['weekendDayPrice'];
		// 	} else {
		// 		$OTAmount = $prices['dayPrice'];
		// 	}
		// } else {
		// 	$OTAmount = $OTHour * $prices['OTHourPrice'];
		// }


		// $earlyHour = CHECKIN_DEFAULT - $checkinTime->format('H');
		// if ($earlyHour < 0) {
		// 	$earlyHour = 0;
		// }

		// $earlyAmount = $earlyHour * $prices['earlyHourPrice'];

		// $surcharge = ($orderInfo['numPeople'] - $prices['maxPeople']) * $prices['surcharge'];
		// if ($surcharge < 0) {
		// 	$surcharge = 0;
		// }
		// array_push($result, [
		// 	'totalAmount' => $roomAmount
		// ]);

		// return $result+= ['totalAmount' => $roomAmount];
		return $result;
	}

	public function accountEarlyFee($date){

	}
	public function accountOTFee(){
		
	}

	public function PreparePriceResult($resultArray){
		$results = array();
		foreach($resultArray as $result){
			$results += [$result['chargeType'] => $result['price']];
		}
		return $results;
	}
	public function isWeekend($date)
	{
		return $date->format('N') >= 6;
	}
	function getWeekday(\Datetime $day) {
		$weekday = $day->format("l");
		$weekday = strtolower($weekday);
		switch($weekday) {
			case 'monday':
				$weekday = 'Thứ hai';
				break;
			case 'tuesday':
				$weekday = 'Thứ ba';
				break;
			case 'wednesday':
				$weekday = 'Thứ tư';
				break;
			case 'thursday':
				$weekday = 'Thứ năm';
				break;
			case 'friday':
				$weekday = 'Thứ sáu';
				break;
			case 'saturday':
				$weekday = 'Thứ bảy';
				break;
			default:
				$weekday = 'Chủ nhật';
				break;
		}
		return $weekday.', '.date('d-m-Y');
	}
}