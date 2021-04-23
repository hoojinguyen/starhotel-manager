<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Quotation;
use Romi\Domain\Config;

class PriceQuoteLogic extends BaseLogic
{


	public function getHourPrice($isAfter23h, $isWeekend, $roomType)
	{    
        $prices = $this->getEntityManager()->getRepository(Config::class)->getHourPrice($roomType);
        $firstHour = $prices[0][value];
        $nextHour = $prices[1][value];
        $firstHourAfter23h =$prices[2][value];
        $nextHourAfter23h =$prices[3][value];
        $dayPrice = $prices[4][value];
        $nightPrice = $prices[5][value];
        $weekendDayPrice = $prices[6][value];
        $weekendNightPrice = $prices[7][value];
        $maxPeople = $prices[8][value];
        $surcharge = $prices[9][value];


		if ($isAfter23h == 'on') {
            $firstHour = $firstHourAfter23h;
            $nextHour = $nextHourAfter23h;
            if ($isWeekend == 'on'){
                $allInPrice = $weekendNightPrice;
            } else {
                $allInPrice = $nightPrice;
            }	
        }
        else {
            if ($isWeekend == 'on'){
                $allInPrice = $weekendDayPrice;
            } else {
                $allInPrice = $dayPrice;
            }	

        }
            
	
		return array(
			'firstHour' => $firstHour,
			'nextHour' => $nextHour,
			'maxPeople' => $maxPeople,
			'surcharge' => $surcharge,
			'allInPrice' => $allInPrice
		);
	}
	public function getNightPrice($isWeekend, $roomType)
	{
        $prices = $this->getEntityManager()->getRepository(Config::class)->getNightPrice($roomType);
        $nightPrice = $prices[0][value];
        $weekendNightPrice =$prices[1][value];
        $OTHour = $prices[2][value];
        $earlyHour = $prices[3][value];
        $maxPeople = $prices[4][value];
        $surcharge = $prices[5][value];

        if($isWeekend == 'on'){
            $room = $weekendNightPrice;           
        }
        else{
            $room = $nightPrice;
        }
        
		return array(
			'room' => $room,
			'OTHour' => $OTHour,
			'earlyHour' => $earlyHour,
			'maxPeople' => $maxPeople,
			'surcharge' => $surcharge,

		);
	}
	public function getDayPrice($isWeekend, $roomType)
	{        
        $prices = $this->getEntityManager()->getRepository(Config::class)->getDayPrice($roomType);
        $dayPrice = $prices[0][value];
        $weekendDayPrice =$prices[1][value];
        $OTHour = $prices[2][value];
        $earlyHour = $prices[3][value];
        $maxPeople = $prices[4][value];
        $surcharge = $prices[5][value];

		if ($isWeekend == 'on') {
            $room = $weekendDayPrice;
		} else {
            $room = $dayPrice;
		}
		return array(
			'room' => $room,
			'OTHour' => $OTHour,
			'earlyHour' => $earlyHour,
			'surcharge' => $surcharge,
			'maxPeople' => $maxPeople,
		);
	}
	public function accountByHour($params)
	{
		$surcharge = null;
		$prices = $this->getHourPrice($params['isAfter23h'],$params['isWeekend'], $params['roomType']);
		$hours = $params['hours'];
		$firstHour = $prices['firstHour'];
		$nextHour = $prices['nextHour'];
		$overPeople = $params['people'] - $prices['maxPeople'];
		$total = $firstHour + ($hours - 1) * $nextHour;
		if ($total > $prices['allInPrice'])
			$total = $prices['allInPrice'];
		if ($overPeople <= 0) {
			$surcharge = 0;
		} else {
			$surcharge = $overPeople * $prices['surcharge'];
		}
		
        
        return array(
			'firstHour' => $firstHour,
			'nextHour' => $nextHour * ($hours -1 ),
			'surcharge' => $surcharge,
			'total' => $total + $surcharge,
		);
	}
	public function accountAllIn($params)
	{
		$prices = null;
		$surcharge = null;
		if ($params['rentType'] == "3") {
			$prices = $this->getNightPrice($params['isWeekend'], $params['roomType']);
		} else {
			$prices = $this->getDayPrice($params['isWeekend'], $params['roomType']);
		}

		$OTHour = $params['OTHour'];
		$earlyHour = $params['earlyHour'];
		$overPeople = $params['people'] - $prices['maxPeople'];
		if ($overPeople <= 0) {
			$surcharge = 0;
		} else {
			$surcharge = $overPeople * $prices['surcharge'];
		}
        
        return array(
			'priceDayOrNight' => $prices['room'],
			'earlyHour' => $earlyHour * $prices['earlyHour'],
			'OTHour' => $OTHour * $prices['OTHour'],
            'surcharge' => $surcharge,
            'total' => $prices['room'] + $OTHour * $prices['OTHour'] + $earlyHour * $prices['earlyHour'] + $surcharge,
		);
	}
	public function LogArray($array)
	{
		foreach ($array as $key => $value) {
			$this->logger->info($key . '=>' . $value);
		}
	}
}
